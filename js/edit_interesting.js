function add_elem() {
  $("#interesting").append('<li><input type="text" name="title[]" /><input type="text" name="url[]" /><select name="target[]"><option value="_target">_target</option><option value="_blank" selected="1">_blank</option></select><input type="checkbox" name="visible[]" value="1" checked="1" /><img src="img/clock.png" alt="drag icon" style="cursor:move;" /></li>');
}

$(document).ready(function() {
  $("#interesting").sortable();
  
  $("#delete_row").droppable({
    drop: function(event, ui) {
      if (window.confirm("Czy na pewno chcesz usunąć element?")) {
        ui.draggable.remove();
      }
    }
  });
  
  $("#form_interesting").submit(function() {
    $("#form_interesting input:checkbox").each(function() {
      if (!$(this).is(":checked")) {
        this.value = 0;
        $(this).attr("checked", true);
      }
    });
    return true;
  });
});

