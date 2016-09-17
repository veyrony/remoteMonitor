#!/usr/bin/env python    
# -*- coding: utf-8 -*- 

import smbus
import time

bus = smbus.SMBus(1)

BUSNUM              = 1
LM75_I2C_ADDR		= 0x48
LM75_TEMP_REG		= 0x00
LM75_CONF_REG		= 0x01
LM75_THYST_REG		= 0x02
LM75_TOS_REG		= 0x03


class LM75Sensor(obj):
	def __init__(self, address = LM75_I2C_ADDR, tempAddress = LM75_TEMP_REG ,busNum = BUSNUM):
		self._address = address
		self._busNum = smbus.SMBus(busNum)
		self._tempAddress = tempAddress

	def reg2temperature(self, data):
		tmp = (data&0xFFFF)
		return tmp / 8.0

	def try_read_reg(self, regAddr):
		result = None
		try:
			result = self._busNum.read_word_data(self._address, regAddr) & 0xFFFF
		except IOError as err:
			print err
		return result

	def getTemperature (self):
		reg = self.try_read_reg(self._tempAddress)
		reg = ( ( (reg << 8)&0xFF00 ) + (reg>>8) )>>5   #TempReg[16:5] is temperature data 
		temp = self.reg2temperature(reg)
		return temp

