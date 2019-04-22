from sys import byteorder
from array import array
from struct import pack

import sys
import time
import pyaudio
import wave
import RPi.GPIO as GPIO
import time

THRESHOLD = 10#600
SILENCE_COUNT = 11#30

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
RED = 12
GREEN = 11
BLUE = 13
GPIO.setup(RED,GPIO.OUT)
GPIO.setup(GREEN,GPIO.OUT)
GPIO.setup(BLUE,GPIO.OUT)

GPIO.output(GREEN,GPIO.HIGH)

filepath = '/var/www/html/configs.txt'  
with open(filepath) as fp:  
   line = fp.readline()
   cnt = 1
   while line:
       line = fp.readline()
       if cnt == 1:
           THRESHOLD = int(line)
           cnt += 1
       elif cnt == 2:
           SILENCE_COUNT = int(line)
           cnt += 1
       
       
print("THRESHOLD: ")
print(THRESHOLD)
print("SILENCE_COUNT: ")
print(SILENCE_COUNT)
#sys.exit()

OUT_MAX_SND = 1
OUT_COUNT_SILENCE = 1

#THRESHOLD = 600#600
#SILENCE_COUNT = 30#30

form_1 = pyaudio.paInt16 # 16-bit resolution
chans = 1 # 1 channel
samp_rate = 44100 # 44.1kHz sampling rate 44100 
chunk = 4048 # 2^12 samples for buffer
record_secs = 5 # seconds to record
dev_index = 2 # device index found by p.get_device_info_by_index(ii)

def is_silent(snd_data):
    "Returns 'True' if below the 'silent' threshold"
    return max(snd_data) < THRESHOLD

def normalize(snd_data):
    "Average the volume out"
    MAXIMUM = 16384
    times = float(MAXIMUM)/max(abs(i) for i in snd_data)

    r = array('h')
    for i in snd_data:
        r.append(int(i*times))
    return r

def trim(snd_data):
    "Trim the blank spots at the start and end"
    def _trim(snd_data):
        snd_started = False
        r = array('h')

        for i in snd_data:
            if not snd_started and abs(i)>THRESHOLD:
                snd_started = True
                r.append(i)

            elif snd_started:
                r.append(i)
        return r

    # Trim to the left
    snd_data = _trim(snd_data)

    # Trim to the right
    snd_data.reverse()
    snd_data = _trim(snd_data)
    snd_data.reverse()
    return snd_data

def add_silence(snd_data, seconds):
    "Add silence to the start and end of 'snd_data' of length 'seconds' (float)"
    r = array('h', [0 for i in range(int(seconds*samp_rate))])
    r.extend(snd_data)
    r.extend([0 for i in range(int(seconds*samp_rate))])
    return r

def record():
    """
    Record a word or words from the microphone and
    return the data as an array of signed shorts.

    Normalizes the audio, trims silence from the
    start and end, and pads with 0.5 seconds of
    blank sound to make sure VLC et al can play
    it without getting chopped off.
    """
    p = pyaudio.PyAudio()
    
    stream = p.open(format = form_1,rate = samp_rate,channels = chans, \
                    input_device_index = dev_index,input = True, \
                    frames_per_buffer=chunk)

    num_silent = 0
    snd_started = False

    r = array('h')

    while 1:
        # little endian, signed short
        snd_data = array('h', stream.read(chunk, exception_on_overflow = False))
        if byteorder == 'big':
            snd_data.byteswap()
        r.extend(snd_data)
        #print(max(snd_data))
        silent = is_silent(snd_data)

        if silent and snd_started:
            print(num_silent)
            num_silent += 1
            GPIO.output(RED,GPIO.LOW)
            GPIO.output(BLUE,GPIO.HIGH)
            GPIO.output(GREEN,GPIO.LOW)
        elif not silent and not snd_started:
            snd_started = True
        elif not silent and snd_started:
            num_silent = 0
            GPIO.output(RED,GPIO.HIGH)
            GPIO.output(BLUE,GPIO.LOW)
            GPIO.output(GREEN,GPIO.LOW)

        if snd_started and num_silent > SILENCE_COUNT:
            print("Start saving")
            GPIO.output(RED,GPIO.LOW)
            GPIO.output(BLUE,GPIO.LOW)
            GPIO.output(GREEN,GPIO.HIGH)
            break

    sample_width = p.get_sample_size(form_1)
    stream.stop_stream()
    stream.close()
    p.terminate()

    #r = normalize(r)
    r = trim(r)
    r = add_silence(r, 0.5)
    return sample_width, r

def record_to_file(path):
    "Records from the microphone and outputs the resulting data to 'path'"
    sample_width, data = record()
    data = pack('<' + ('h'*len(data)), *data)

    wf = wave.open(path, 'wb')
    wf.setnchannels(2)
    wf.setsampwidth(sample_width)
    wf.setframerate(samp_rate/2)
    wf.writeframes(data)
    wf.close()

if __name__ == '__main__':
    while(True):
        print("please speak a word into the microphone")
        timestr = time.strftime("%Y%m%d-%H%M%S")
        name = "/var/www/html/sounds/"+timestr+".wav"
        record_to_file(name)
        print("done - result written to ",name)

