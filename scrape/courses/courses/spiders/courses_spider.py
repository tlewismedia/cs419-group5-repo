# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html


# DIRECTIONS: 
# 1) install Scrapy
# 2) edit domain and start url to correct address
# 3) run this script with: scrapy crawl courses -o items.json

# this script works with the following URL: http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FES"termcode=201500&campus=corvallis&columns=bcfjk
# all urls must have similar url arguments

import scrapy
import re

from courses.items import CourseItem

class CoursesSpider(scrapy.Spider):
    name = "courses"
    allowed_domains = ["catalog.oregonstate.edu"]
    start_urls = [
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=NE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ALS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=NUTR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AHE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FOR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=OC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AREC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AED&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=GS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PAX&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AG&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=GPH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PHAR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ANS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=GEO&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PHL&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ANTH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=GRAD&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PAC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=AEC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ART&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HHS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PBG&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ATS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HST&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HSTS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PSY&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BB&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BEE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HORT&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=PPOL&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BIOE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=HDFS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BI&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=QS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BRR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BOT&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IEPA&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=RHP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BA&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IEPH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=RNG&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IEPG&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=RS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CHE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IST&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=INTL&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SED&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CHN&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=IT&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SOC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CCE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SOIL&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=JPN&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SPAN&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=COMM&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ST&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=LA&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SUS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CEM&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=LS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=SNR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=CROP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MRM&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=TCE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=DHE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MPP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=TA&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MATS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=TOX&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ECON&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MTH&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ECE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ME&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=VMB&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ENGR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=VMC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ENG&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MB&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ENT&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WRE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ENVE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MCB&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WRP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ENSC&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MUS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WRS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ES&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MUP&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WGSS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=EXSS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=MUED&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WSE&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=WR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FILM&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=NR&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FW&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=NS&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ZZ&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=FST&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=NMC&termcode=201500&campus=corvallis&columns=bcfjk"

    ]

    # allowed_domains = ["localhost-scrapy"]
    # start_urls = [
    #     "http://localhost-scrapy"
    # ]

    def parse(self, response):


        print "########### PARSE ###########"

        # goal is to first grab the tables where the first row has a tr 
        # and the first th is 'Term'

        i = 0
        tables = response.xpath('//table')

        for table in tables:

    

            
            i += 1

            if i == 7 or i == 6: continue


            # print table.xpath('.//th/font/b/text()').extract()
            header = table.xpath('.//th/font/b/text()')
            # print "header: "
            
            
            if not header: continue

            # print header

            print '\n\n\n*********************\n'
            print "TABLE: " + str(i) 
            


            print "header[0]: " + header[0].extract()

            if header[0].extract() != "StartDate" : continue

            

            # at this point we know we want this table

            # get rows from this table
            rows = table.xpath('./tr')

            # print "ROWS: "
            # print rows
            
            for row in rows:
                
                

                # if it's a row with no td's then skip
                if not row.xpath('./td/font/text()').extract(): continue               
                

                 # discard courses with TBA dates
                days = re.findall( '[A-Z]+' , row.xpath('./td/font/text()')[4].extract())
                if days[0] == 'TBA' : continue 

                # discard courses with no profs
                prof = row.xpath('./td/font/text()')[3].extract()
                print "prof"
                print prof

                if not re.match('[a-zA-Z0-9_]',prof): continue
                
                
                item = CourseItem()

                item['days'] = days[0]
                item['startDate'] = row.xpath('./td/font/text()')[0].extract()
                item['endDate'] = row.xpath('./td/font/text()')[1].extract()
                item['crn'] = row.xpath('./td/font/text()')[2].extract()            
                item['prof'] = prof
                times = re.findall( '[\d-]+' , row.xpath('./td/font/text()')[4].extract())[0]
                startTime = times[0:4]
                endTime = times[5:9]
                item['startTime'] = startTime
                item['endTime'] = endTime

                yield item


