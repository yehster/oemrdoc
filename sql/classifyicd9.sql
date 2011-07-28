update codes set code_text_short="1" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and cast(codes.code as decimal) <= 139;
update codes set code_text_short="2" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 140 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 239;
update codes set code_text_short="3" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 240 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 279;
update codes set code_text_short="4" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 280 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 289;
update codes set code_text_short="5" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 290 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 319;
update codes set code_text_short="6" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 320 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 389;
update codes set code_text_short="7" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 390 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 459;
update codes set code_text_short="8" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 460 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 519;
update codes set code_text_short="9" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 520 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 579;
update codes set code_text_short="10" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 580 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 629;
update codes set code_text_short="11" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 630 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 679;
update codes set code_text_short="12" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 680 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 709;
update codes set code_text_short="13" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 710 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 739;
update codes set code_text_short="14" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 740 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 759;
update codes set code_text_short="15" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 760 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 779;
update codes set code_text_short="16" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 780 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 799;
update codes set code_text_short="17" where  not (codes.code like "E%" or codes.code like "V%") and code_type=2 and 800 <=cast(codes.code as decimal) and cast(codes.code as decimal)<= 999;
update codes set code_text_short="E" where  (codes.code like "E%") and code_type=2;
update codes set code_text_short="V" where  (codes.code like "V%") and code_type=2;







