// Functions 
function togglize() {
    $(".toggleable-btn").addClass('hide');
    $("#" + $(this).attr('id') + " .toggleable-btn").removeClass('hide');
}

function untogglize() {
    $("#" + $(this).attr('id') + " .toggleable-btn").addClass('hide');
}

function removeFile(id, type) {
    // Get picture path to remove
    var picture = $("#" + type + '-' + id).attr('src').split('?')[0];
    // Get all pictures converted in array
    var pictures = $("#files_to_add_" + type).val().split(',');
    // Get picture index in pictures array
    var index = pictures.indexOf(picture);
    // Remove picture to pictures
    if (index !== -1) pictures.splice(index, 1);
    // Update filesToPictrue value
    $("#files_to_add_" + type).val(pictures.join(','));
    var nextLiId = parseInt(id) + 1;
    $("#li-" + nextLiId).removeClass('uk-grid-margin');
    // Remove DOM Element
    $("#li-" + id).fadeOut();
    console.log($("#files_to_add_picture").val());
}

$("#choose_files_btn").click(function(e){
    $("#choose_files_modal").show();
});

$(".remove-picture").click(function(e){
    removeFile($(this).attr('id'), 'picture');
});

$("#save").html("<i class=\"icon-save\"></i>");

// Show ajax content in a modal
$( ".show-modal").click(function( event ) {
    event.preventDefault();
    var groupId = $(this).attr('id'),
        url = $(this).attr( "href" );

    $.get( url).done(function( data ) {
        var modal = $("#showModal").modal("show");
        modal.show();
        $("#showModal").html(data);
    });
});

// Remove row in table
$(".remove-row").click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');

    $.get(url, function(response){
        $(this).closest('tr').fadeOut();
    }).fail(function(e){
        alert(e.responseText);
    });
});

// Show more
$("#show_more").click(function(e){
    e.preventDefault();
    $(this).addClass('hide');
    $(".togglable").each(function(){
        $(this).removeClass("hide");
    })
});

// Delete item
$(".delete-item").click(function(e){
    e.preventDefault();

    var id = $(this).attr('id');
    var url = $(this).attr('href');
    var label = $("#label-" + id).val();

    swal({
        title: "Etes-vous sûr de vouloir supprimer " + label, 
        /*text: label,*/ 
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        cancelButtonText : 'Annuler',
        confirmButtonText: "Oui, supprimer",
        confirmButtonColor: "#f8bc86"
    }, function() {
        $.ajax({
            url:  url
        })
        .done(function(data) {
            swal({
                title: "Supprimé", 
                text: "La suppression s'est effectuée avec succès", 
                type: "success"
            },function() {
                location.reload();
            });
        })
        .error(function(data) {
            swal("Oops", data.statusText, "error");
        });
    });
});

// Delete list
$(".delete-list").click(function(e){
    e.preventDefault();
    var routeName = $('#delete-route-name').val();
    var ids = [];

    $(".toggleable-btn-select.checked > i").each(function() {
        ids.push($(this).attr("id").replace('toggle-item-', ''));
    });
    
    swal({
        title: "Etes-vous sûr de vouloir supprimer la liste ?", 
        /*text: label,*/ 
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        cancelButtonText : 'Annuler',
        confirmButtonText: "Oui, supprimer",
        confirmButtonColor: "#f8bc86"
    }, function() {
        for (var i = 0; i < ids.length; i++) {
            $.ajax({
                url:  Routing.generate(routeName, { 'id' : i })
            });
        }
        
        location.reload();
    });
});

// Remove list
$(".remove-list").click(function(e){
    e.preventDefault();
    var ids = [];
    var routeName = $('#remove-route-name').val();
    var routeParam = $('#remove-route-param').val();
    var successed = 0,
        failded = 0;
    
    $(".toggleable-btn-select.checked > i").each(function() {
        ids.push($(this).attr("id").replace('toggle-item-', ''));
    });
    
    swal({
        title: "Etes-vous sûr de vouloir supprimer la liste ?", 
        /*text: label,*/ 
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        cancelButtonText : 'Annuler',
        confirmButtonText: "Oui, supprimer",
        confirmButtonColor: "#f8bc86"
    }, function() {
        for (var i = 0; i < ids.length; i++) {
            $.ajax({
                url:  Routing.generate(routeName, { 'id' : routeParam , 'item' : i })
            })
            .done(function(data) {
                successed++;
            })
            .error(function(data) {
                swal("Oops", data.statusText, "error");
            });
        }
        
        swal({
            title: "Ajout terminé", 
            text: successed + " éléments ont été retirés avec succès.", 
            type: "success"
        });
    });
    
});


// Mutiple select or unselect
$("#toggle-check").click(function(e){
    e.preventDefault();

    if(! $(this).hasClass("all-checked")){
        var isCheck = 0;
        $(this).addClass("all-checked");
        $(this).find('i:first').addClass('icon-checkbox-checked');
        $(this).find('i:first').removeClass('icon-checkbox-unchecked');
        $("#toggle-items-checked-count").val($("#toggle-items-count").val());
    }else {
        var isCheck = 1;
        $(this).removeClass("all-checked");
        $(this).find('i:first').addClass('icon-checkbox-unchecked');
        $(this).find('i:first').removeClass('icon-checkbox-checked');
        $("#toggle-items-checked-count").val(0);
    }

    $(".toggleable-btn-select").each(function(){
        if(isCheck == 0){
            $(this).addClass("checked");
            $(this).removeClass('hide');
            $(this).find('i:first').addClass('icon-checkbox-checked');
            $(this).find('i:first').removeClass('icon-checkbox-unchecked');
            $("#toggle-check-text").html("Annuler");
            $(".toggle-action").removeClass('hide');
            // Detach mouseenter and mouseover events
            $("body").off('mouseenter', '.toggleable', togglize);
            $("body").off('mouseleave', '.toggleable', untogglize);
        }else{
            $(this).removeClass("checked");
            $(this).addClass('hide');
            $(this).find('i:first').addClass('icon-checkbox-unchecked');
            $(this).find('i:first').removeClass('icon-checkbox-checked');
            $("#toggle-check-text").html("Tout sélectionner");
            $(".toggle-action").addClass('hide');
            // Attach mouseenter and mouseover events
            $("body").on('mouseenter', '.toggleable', togglize);
            $("body").on('mouseleave', '.toggleable', untogglize);
        }
    });
    console.log();
});

// Mutiple select or unselect
$(".toggleable-btn-select").click(function(e){
    e.preventDefault();
    var toggleItemCheckedCount = parseInt($("#toggle-items-checked-count").val());
    
    if (! $(this).hasClass("checked")){
        var isCheck = 0;
        $(this).addClass("checked");
    }else {
        var isCheck = 1;
        $(this).removeClass("checked");
    }

    if(isCheck == 0){
        toggleItemCheckedCount++;
        // Change icon
        $(this).find('i:first').addClass('icon-checkbox-checked');
        $(this).find('i:first').removeClass('icon-checkbox-unchecked');
        // Update toggle item checked count
        $("#toggle-items-checked-count").val(toggleItemCheckedCount);
        // Update multiple select icon and text
        if ($("#toggle-items-count").val() == toggleItemCheckedCount) {
            $("#toggle-check").addClass("all-checked");
            $("#toggle-check-icon").addClass('icon-checkbox-checked');
            $("#toggle-check-text").html("Annuler");
        }
        
        $(".toggle-action").removeClass('hide');
        $(".toggleable-btn-select").removeClass('hide');
        $(".toggleable-btn-controls").addClass('hide');
        // Detached event to current element
        $("body").off('mouseenter', '.toggleable', togglize);
        $("body").off('mouseleave', '.toggleable', untogglize);
    }else{
        toggleItemCheckedCount--
        $(this).find('i:first').addClass('icon-checkbox-unchecked');
        $(this).find('i:first').removeClass('icon-checkbox-checked');
        $("#toggle-items-checked-count").val(toggleItemCheckedCount);
        $("#toggle-check").removeClass("all-checked");
        $("#toggle-check-icon").addClass('icon-checkbox-unchecked');
        if (toggleItemCheckedCount <= 0) {
            $("#toggle-check-text").html("Tout Selectionner");
            $(".toggle-action").addClass('hide');
            $("body").on('mouseenter', '.toggleable', togglize);
            $("body").on('mouseleave', '.toggleable', untogglize);
        }else {
            $("#toggle-check-text").html("Annuler");
        }
    }
    
    var ids = [];
    $(".toggleable-btn-select.checked > i").each(function(){
        ids.push($(this).attr("id").replace('toggle-item-', ''));
    });
    
    $("#toggle-items-checked-id").val(ids.join(','));
});

// Slufify
$('.slug').focus(function(){
    var slug = $('.slugglable').val().toLowerCase().replace(/\s/g,'-');
    $(this).val(slug);
});

