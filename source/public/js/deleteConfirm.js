/**
 * リソーの削除時に確認メッセージを表示する。
 * 
 * @param {*} id => 削除フォームのID
 */
function deleteConfirm(id) {
    let deleteForm = document.getElementById('delete_form' + id);

    if (confirm('削除してもよろしいでしょうか？')) {
        deleteForm.submit();
    }
}