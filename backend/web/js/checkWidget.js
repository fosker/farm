$('.list-group > label > input').change(function (e) {
    var status = this.checked;
   $(e.target).parent().next().children().each(function(i, elem) {
       var checkbox = $(elem).find('label > input');
       if(status) {
           checkbox.prop('checked', status);
       } else {
           checkbox.removeAttr('checked');
       }
   });
});