
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/app.js')}}"></script>

    <script>

        var base_url = "{{ Config::get('app.url') }}";

        $(document).ready(function(){

            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            })  
        });


    </script>

    <script src="{{asset('js/frontend_print.js?dd=1')}}"></script>

</body>
</html>