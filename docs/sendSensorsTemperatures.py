#!/usr/bin/python
# -*- coding: utf-8 -*-

import glob, requests, os, sys
from datetime import datetime

session = requests.Session()

RASPI_SENSORS_URL = "https://my.website.tld/" # Your URL to access to raspi-sensors
API_KEY = "xxx" # Fill the api_key value of raspi-sensors


def get_temp(device_file):
    temp_c = 0

    if os.path.isfile(device_file):
        f = open(device_file, 'r')
        lines = f.readlines()
        f.close()

        if lines[0].strip()[-3:] == 'YES':
            equals_pos = lines[1].find('t=')

            if equals_pos != -1:
                temp_string = lines[1][equals_pos+2:]
                temp_c = float(temp_string) / 1000.0

    return temp_c


base_dir = '/sys/bus/w1/devices/'
devices_folder = glob.glob(base_dir + '28*')

for device_dir in devices_folder:
    device = os.path.basename(device_dir)

    temp = round(get_temp(device_dir+'/w1_slave'), 1)

    #print device+' : '+str(temp)

    # POST send
    date = datetime.strftime(datetime.now(), '%Y-%m-%d %H:%M:%S')

    post_data = {'key': API_KEY, 'sensor': device, 'value': temp, 'date': date}

    post_request = session.post(url=RASPI_SENSORS_URL+'api.php', data=post_data)