function headerClick()
{
    window.alert($(this).text());
}
function registerDashboardEvents()
{
    $(".dashboard th").click(headerClick);
}
registerDashboardEvents();