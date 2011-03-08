XButton2::
MouseGetPos, X,Y, curWin
WinGetClass, winClass, ahk_id %curWin%
If (winClass="IEFrame")
{
	WinActivate, ahk_id %curWin%
	WinGet, IEID, ID
	WinGetTitle, winTitle, A
	winTitle:=SubStr(winTitle,1,InStr(winTitle,"-"))
	if(InStr(winTitle,"http"))
	{
		winTitle=""
	}
	DevToolID := WinExist(winTitle "ahk_class IEDEVTOOLS")
	if not DevToolID
	{
		Send {F12}
		sleep, 100
		DevToolID := WinExist(winTitle "ahk_class IEDEVTOOLS")
	}
	Else
	{
		;WinActivate, ahk_id %DevToolID%
	}
	WinActivate, ahk_id %DevToolID%
	Sleep, 100
	ControlSend,,^B,ahk_id %DevToolID%
	WinActivate, ahk_id %IEID%
	Sleep, 100
	MouseClick, L, %X%, %Y%
; create if not
; send ctrl B
;refocus on original window
; click
}
return
