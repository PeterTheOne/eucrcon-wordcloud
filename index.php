<?php

$pdoUsers = new PDO('sqlite:responses_users.sqlite');
$pdoUsers->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoUsers->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$statement = $pdoUsers->prepare('SELECT * FROM questions');
$statement->execute();
$questions = $statement->fetchAll();

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">

        <title>eucrcon-wordcloud</title>
    </head>
    <body>
        <h1>eucrcon-wordcloud</h1>

<?php

$i = 1;
foreach ($questions as $question) {
    //echo '<h2>' . $i++ . ': ' . $question->question . '</h2>';
}

?>


        <script src="jquery-1.11.1.min.js"></script>
        <script src="d3.js"></script>
        <script src="d3.layout.cloud.js"></script>
        <script>

            var unicodePunctuationRe = "!-#%-*,-/:;?";
            var wordSeparators = /[\s\u3031-\u3035\u309b\u309c\u30a0\u30fc\uff70]+/g;
            var punctuation = new RegExp("[" + unicodePunctuationRe + "]", "g");
            var maxLength = 250;

            var tags;
            var fontSize;

            $.get('data.php?question=100', function(data) {
                tags = {};
                var cases = {};
                data.split(wordSeparators).forEach(function(word) {
                    word = word.replace(punctuation, "");
                    word = word.substr(0, maxLength);
                    cases[word.toLowerCase()] = word;
                    tags[word = word.toLowerCase()] = (tags[word] || 0) + 1;
                });
                tags = d3.entries(tags).sort(function(a, b) { return b.value - a.value; });
                tags.forEach(function(d) { d.key = cases[d.key]; });

                fontSize = d3.scale['log']().range([10, 100]);
                if (tags.length) fontSize.domain([+tags[tags.length - 1].value || 1, +tags[0].value]);
                tags = tags.slice(0, 250);

                console.log(tags);

                var fill = d3.scale.category20();

                d3.layout.cloud().size([300, 300])
                    .words(
                        tags
                    )
                    .padding(5)
                    .rotate(function() { return ~~(Math.random() * 2) * 90; })
                    .font("Impact")
                    .fontSize(function(d) { return fontSize(+d.value); })
                    .on("end", draw)
                    .start();

                function draw(words) {
                    var vis = d3.select("body").append("svg")
                        .attr("width", 800)
                        .attr("height", 800)
                        .append("g")
                        .attr("transform", "translate(150,150)");
                    var text = vis.selectAll("text")
                        .data(words);
                    text.transition()
                        .duration(1000)
                        .attr("transform", function(d) { return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")"; })
                        .style("font-size", function(d) { return d.size + "px"; });
                    text.enter().append("text")
                        .style("font-size", function(d) { return d.size + "px"; })
                        .style("font-family", "Impact")
                        .style("fill", function(d, i) { return fill(i); })
                        .attr("text-anchor", "middle")
                        .attr("transform", function(d) {
                            return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
                        })
                        .text(function(d) { return d.key; });
                }
            });
        </script>
    </body>
</html>