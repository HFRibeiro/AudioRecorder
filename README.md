# AudioRecorder
```python
git clone http://people.csail.mit.edu/hubert/git/pyaudio.git
sudo apt-get install libportaudio0 libportaudio2 libportaudiocpp0 portaudio19-dev
sudo apt-get install python3-dev python3-rpi.gpio python3-picamera
cd pyaudio
sudo python3 setup.py install
```

```
cd ~
mkdir logs
sudo crontab -e
@reboot sh /home/pi/AudioRecorder/launcher.sh >/home/pi/logs/cronlog 2>&1
```

sudo nano /etc/sudoers
```
www-data ALL = NOPASSWD: /sbin/reboot, /sbin/halt
```