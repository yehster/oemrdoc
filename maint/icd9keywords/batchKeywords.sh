#!/bin/bash
for i in {0..20000..10}
do
	php generateKeywords.php $i 10
done
