# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html


# DIRECTIONS: 
# 1) install Scrapy
# 2) edit domain and start url to correct address
# 3) run this script with: scrapy crawl courses -o items.json

import scrapy

from courses.items import CourseItem

class CoursesSpider(scrapy.Spider):
    name = "courses"
    allowed_domains = ["localhost3"]
    start_urls = [
        "http://localhost-scrapy"
    ]

    def parse(self, response):

        rows = response.xpath('//tr')
        # print '\n\n\n*********************\n'
        # print rows
        # print '\n*********************\n\n'
        
        for sel in rows:
            item = CourseItem()

            # if it's a row with no td's then skip


            if not sel.xpath('./td/text()').extract(): continue



            

            print '\n\n\n*********************\n'
            print 'ROW: '
            print sel.xpath('./td/text()').extract();
            print '\n*********************\n\n'

            
            item['feild1'] = sel.xpath('./td/text()')[0].extract()
            item['feild2'] = sel.xpath('./td/text()')[1].extract()
            item['feild3'] = sel.xpath('./td/text()')[2].extract()
            yield item


