import RPi.GPIO as GPIO
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
RED = 12
GREEN = 11
BLUE = 13
GPIO.setup(RED,GPIO.OUT)
GPIO.setup(GREEN,GPIO.OUT)
GPIO.setup(BLUE,GPIO.OUT)

try:
        while True:
				print "LED on"
				GPIO.output(RED,GPIO.HIGH)
				time.sleep(1)
				print "LED off"
				GPIO.output(RED,GPIO.LOW)
				time.sleep(1)
except KeyboardInterrupt:
        GPIO.output(RED,GPIO.LOW)
        GPIO.output(GREEN,GPIO.LOW)
        GPIO.output(BLUE,GPIO.LOW)
        GPIO.cleanup()
        


