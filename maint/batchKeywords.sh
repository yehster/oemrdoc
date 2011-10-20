#!/bin/bash
for i in {0..15000..100}
do
	php genKeywords.php $i 100
done
