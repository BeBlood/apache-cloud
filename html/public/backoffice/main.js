var colorMapping = {};
var colors = [
    '#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5',
    '#2196F3', '#03A9F4', '#00BCD4', '#009688', '#4CAF50',
    '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', '#FF9800',
    '#FF5722', '#795548', '#9E9E9E', '#607D8B'
];


$("#permissionsHead").find(".permissionTag").each(function(i, e){
    colorMapping[$(this).attr("data-perm")] = colors[Math.floor(Math.random()*colors.length)];
})

$("#permissionsBody").find(".permissionTag").each(function(i, e){
    var me = $(this);
    if (me.hasClass('active')) {
        me.css('color', 'white');
        me.css('background-color', colorMapping[me.attr("data-perm")]);
    }
})

var addUserFunction = function(){
    var template = '<tr class="addState">' +
                   '<td><span class="iconUser"></span><span contenteditable="true" class="userName"></span></td>' +
                   '<td><div class="permissionTag active" data-perm="view">View</div></td>' +
                   '<td><div class="permissionTag" data-perm="edit">Edit</div></td>' +
                   '<td><div class="permissionTag" data-perm="delete">Delete</div></td>' +
                   '<td><div class="permissionTag" data-perm="owner">Owner</div></td>' +
                   '<td><div class="permissionTag" data-perm="admin">Admin</div></td>' +
                   '<td><a href="#" class="iconRemove deleteUser" title="Remove this user"></a></td>' +
                 '</tr>';
    var user = $(template);
    $("#permissionsBody").prepend(user);

    setTimeout(function(){
        user.removeClass("addState");
    }, 50);

    user.find(".userName").trigger("focus");
    return false;
}

$("#permissionWrapper").on("click", ".addUser", addUserFunction);

$("#permissionsBody").on("focusin", ".userName", function(){
    $(this).parent().parent().addClass("focused");
}).on("focusout", ".userName", function(){
    $(this).parent().parent().removeClass("focused");
}).on("click", ".deleteUser", function(){
    var parent = $(this).parent().parent();
    parent.addClass("removeState");
    setTimeout(function(){
        parent.remove();
    }, 400);
});

// trigger root permission state
$("#permissionsBody").on("click", ".permissionTag", function(){
    var me   = $(this);

    if(me.hasClass("active")){
        me.removeClass("active");
        me.css('color', '#5A5A5A');
        me.css('background-color', 'initial');
    } else {
        me.addClass("active");
        me.css('color', 'white');
        me.css('background-color', colorMapping[me.attr("data-perm")]);
    }
});

// init filter inputs --------------------------------------------------------------------
$("#permissionWrapper").on("keyup", ".listFilterInput", function(){
    var me    = $(this);
    var val   = $.trim(me.val());
    var items = $("#" + me.attr("id").replace("input", "list")).find("tr");

    if (val.length > 0) {
        var item = null;

        $.each(items, function(i, e){
            item = $(e);
            if (!item.hasClass("doNotFilter")) {
                (item.text().toUpperCase().indexOf(val.toUpperCase()) >= 0) ? item.show()
                : item.hide();
            }
        });
    } else {
        items.show();
    }
});
