$(document).ready(
        function () {
            $(setInterval(
                    function () {
                        $("#datas").load("?p=data&id=1");
                    }
            , 3000));
        });