function strikeThroughEntry(entryUUID)
{
    rootEntry=$("[uuid='"+entryUUID+"']");
    label=rootEntry.find("[type='label']");
    if(label.count>0)
        {
                label.html("<del>"+label.html()+"</del>");
        }
        else
            {
                rootEntry.html("<del>"+rootEntry.html()+"</del>");                
            }
}