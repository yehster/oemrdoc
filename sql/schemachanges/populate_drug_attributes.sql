truncate table dct_drug_attributes;
insert into dct_drug_attributes(rxcui,atn,atv) select rxcui, atn, lcase(atv)  a from rxnsat where ATN='DDF'  group by rxcui, atn having count(distinct(a))=1;
insert into dct_drug_attributes(rxcui,atn,atv) select rxcui, atn, atv  a from rxnsat where ATN in ('DDFA','DRT','DST','DRTA')  group by rxcui, atn having count(distinct(a))=1;