function strikeThroughEntry(entryUUID)
{
    rootEntry=$("[uuid='"+entryUUID+"']");
    label=rootEntry.find("[type='label']");
    label.html("<del>"+label.html()+"</del>");
}