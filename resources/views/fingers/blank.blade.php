<script src="{{asset('/assets/js/jquery-3.5.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        localStorage.setItem("action", "{{$action}}");
        localStorage.setItem("login_session",'true');
        console.log(localStorage.getItem('action'));
        window.close();
    });
</script>
