# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy

class CourseItem(scrapy.Item):
    crn = scrapy.Field()
    prof = scrapy.Field()
    days = scrapy.Field()
    startTime = scrapy.Field()
    endTime = scrapy.Field()
    startDate = scrapy.Field()
    endDate = scrapy.Field()
    
