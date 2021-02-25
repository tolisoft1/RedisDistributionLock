#!/bin/bash

#并发数
count=10000

for(( i = 0; i < ${count}; i++ ))  
do  
{  
	 /Applications/MAMP/bin/php/php7.1.20/bin/php  /Users/fql/develop/RedisDistributionLock/src/testStock.php
}&  
done
#等待循环结束再执行wait后面的内容
wait


commands2


#显示脚本执行耗时
echo -e "time-consuming: $SECONDS    seconds"
