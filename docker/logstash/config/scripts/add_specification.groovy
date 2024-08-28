int index = -1;
if (ctx._source.specifications == null) 
{
    ctx._source.specifications = new ArrayList();
} 
else
{
for (int i = 0; i < ctx._source.specifications.size(); i++) {
    if (ctx._source.specifications[i].id == params.event.get('specification').id) {
    index = i;
    break;
    }
}
}

if (index != -1) {
    ctx._source.specifications[index] = params.event.get('specification');
} else {
    ctx._source.specifications.add(params.event.get('specification'));
}