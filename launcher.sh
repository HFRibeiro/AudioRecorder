#!/bin/sh
# launcher.sh
# navigate to home directory, then to this directory, then execute python script, then back home

cd /
cd home/pi/AudioRecorder
sudo python3 audio_rasp_loop.py
cd /
