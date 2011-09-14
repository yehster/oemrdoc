truncate table dct_drug_attributes;
insert into dct_drug_attributes(RXCUI,ATN,ATV) select RXCUI, ATN, min(ATV) as ATV from rxnsat where ATN in ('DDF','DDFA','DRT','DRTA','DST') and SAB='MMSL' group by RXCUI, ATN;
