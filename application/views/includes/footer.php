 <!-- footer content -->
        <footer>
          <div class="pull-right">
            Jobs
          </div>
          <div class="clearfix"></div>
        </footer>
        </div>
        </div>
	<script src="<?=base_url("assets/js/jquery.min.js"); ?>"></script>
	<script src="<?=base_url("assets/js/bootstrap.min.js"); ?>"></script>
	<script src="<?=base_url("assets/js/custom.min.js"); ?>"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
	<script>
	    $(document).ready(function() {
              $('#editor').summernote({
                    height: 200,
                    toolbar: [
                                // [groupName, [list of button]]
                                ['style', ['bold', 'italic', 'underline', 'clear']],
                                ['font', ['strikethrough', 'superscript', 'subscript','fontname']],
                                ['fontsize', ['fontsize']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph','style']],
                                ['height', ['height']],
                                ['insert',['picture','link','video','table','hr']],
                                ['misc',['codeview','undo','redo']]
                              ],
                    codemirror: { // codemirror options
                                    theme: 'monokai'
                                  }
              });
              $('#editor').on('summernote.paste', function (customEvent, nativeEvent) {
                setTimeout(function () {
                $('.note-editable').selectText();
                $("#editor").summernote("removeFormat");
                }, 100);
                });
            });
            
	</script>
            
    </script>

</body>
</html>