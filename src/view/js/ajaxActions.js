function postComment(parentId)
{
    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.getElementById(parentId));

    xhr.open('POST','src/postComment.php', true);
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200) {
            var newElement = document.createElement('div');
            newElement.innerHTML = xhr.responseText;
            document.getElementById("children" + parentId).insertBefore(newElement, document.getElementById("children" + parentId).firstChild);
        }
    }
    xhr.send(formData)
}

function deleteComment(id)
{
    var xhr = new XMLHttpRequest();
    var body = 'id=' + encodeURIComponent(id);
    xhr.open('POST','src/deleteComment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200) {
            document.getElementById("comment" + id).remove();
        }
    }
    xhr.send(body)
}
