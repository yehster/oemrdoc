insert into dct_drug_attributes(rxcui,atn,atv) select rxcui, atn, max(atv) from
(select a.RXCUI, a.ATN, a.ATV, m.mx from rxnsat a , (select RXCUI, ATN, max(num) as mx from 
(select RXCUI, ATN, count(SAB) as num from rxnsat where ATN in ('DDF','DDFA','DST','DRT','DRTA') group by RXCUI, ATN, ATV) as cnt 
group BY RXCUI,ATN) m
where a.rxcui = m.rxcui and a.ATN=m.ATN group by a.rxcui,a.atn,a.atv having count(a.atv)=m.mx) 
as vals group by rxcui, atn;