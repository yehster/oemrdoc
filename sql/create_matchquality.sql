-- --------------------------------------------------------------------------------
-- Routine DDL
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` FUNCTION `MatchQuality`(searchStr varchar(255),matchStr varchar(3000)) RETURNS int(11)
    DETERMINISTIC
BEGIN
            DECLARE idx,pos,qual ,lastMatch,bonus,searchStrLen INT;
            DECLARE allMatched,inOrder BOOL;
            DECLARE srch CHAR;
            SET allMatched = TRUE,inOrder = TRUE;
            SET qual = 0, idx = 1, bonus = 0, lastMatch = 0;
            SET searchStrLen = CHAR_LENGTH(searchStr);
            WHILE idx <= searchStrLen DO
                SET srch = SUBSTRING(searchStr,idx,1);
                SET pos = LOCATE(srch,matchStr);
                if idx = 1 THEN
                    if pos = 1 THEN
                        SET qual = qual + 10000;
                    ELSEIF pos > 0 THEN
                        if SUBSTRING(matchStr,POS - 1,1) = " " THEN
                            SET qual = qual + 10000;
                        END IF;
                    end if;
                ELSEIF idx=searchStrLen THEN
                    IF SUBSTR(matchStr,CHAR_LENGTH(matchStr),1) = srch THEN
                        SET qual = qual + 5;
                    END IF;
                end if;
                IF pos > 0 THEN
                    IF pos > lastMatch THEN
                        if pos - lastMatch < 3 THEN
                          SET qual = qual + 100;
                        ELSE
                            SET qual = qual + 90, bonus = 0;
                        END IF;
                        if not inOrder THEN
                            set qual=qual - 50;
                        END IF;
                    ELSE
                        SET qual = qual + 30, bonus = 0;
                        IF idx > 1 THEN
                            set inOrder = FALSE;
                        END IF;
                    END IF;
                    SET matchStr = concat(substr(matchStr,1,pos-1),"~",substr(matchStr,pos+1));
                    SET lastMatch = pos;
                ELSE
                    SET bonus = 0, allMatched = false;
                    SET lastMatch=1000;
                END IF;
                SET idx = idx + 1;
            END WHILE;
            if allMatched THEN
                set QUAL = QUAL + 100000;
                if inOrder THEN
                    SET QUAL = QUAL + 1000000;
                END IF;

            END IF;
            RETURN qual;
        END
