# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html


# NOTE: after installing Scrapy, run this script with: scrapy crawl dmoz -o items.json

import scrapy

from tutorial.items import DmozItem

class DmozSpider(scrapy.Spider):
    name = "dmoz"
    allowed_domains = ["dmoz.org"]
    start_urls = [
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=OC&termcode=201500&campus=corvallis&columns=afghijklmnopqrstuvyz"
    ]

    def parse(self, response):
        for sel in response.xpath('//tr'):
            item = CourseItem()
            item['td'] = sel.xpath('td/text()').extract()
            yield item
