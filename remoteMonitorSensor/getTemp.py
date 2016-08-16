#!/usr/bin/env python
# -*- coding:utf-8 -*-

import LM75Sensor

LM75= LM75Sensor.LM75Sensor()

print LM75.getTemperature()