<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-keyword Autocomplete Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .tags-input {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px;
            background-color: #fff;
        }

        .tags-input .tag {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            
        }

        .tags-input .tag .remove-tag {
            cursor: pointer;
            font-size: 14px;
        }

        #suggestions-box {
            position: absolute;
            border: 1px solid #ced4da;
            border-top: none;
            background: #fff;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2>Multi-keyword Autocomplete Search</h2>
        <div class="tags-input">
            <input type="text" id="search-box" class="form-control" placeholder="Enter keywords..." autocomplete="off">
        </div>
        <div id="suggestions-box" class="list-group"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            const keywords = [
                'JavaScript', 'Java', 'Python', 'HTML', 'CSS', 'React', 'Angular', 'Vue.js',
                'Node.js', 'Express', 'MongoDB', 'MySQL', 'PHP', 'Laravel', 'Django'
            ];

            $("#search-box").autocomplete({
                source: keywords,tag
                select: function(event, ui) {
                    addTag(ui.item.value);
                    $("#search-box").val('');
                    return false;
                }
            });

            function addTag(tag) {
                if (!$(".tags-input .tag").filter(function() {
                        return $(this).text().trim() === tag;
                    }).length) {
                    const tagDiv = $('<div class=""></div>').text(tag);
                    const removeButton = $('<span class="remove-tag">×</span>');
                    removeButton.on('click', function() {
                        $(this).parent().remove();
                    });
                    tagDiv.append(removeButton);
                    $(".tags-input").append(tagDiv);
                }
            }
        });
    </script>
</body>

</html>
