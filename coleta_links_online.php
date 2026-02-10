<?php

$html = '
<html>
  <body>
    <table>
      <tr>
        <td><a href="https://site1.com/artigo-php">Como usar PHP para scraping</a></td>
      </tr>
      <tr>
        <td><a href="https://site2.com/laravel">Introdução ao Laravel</a></td>
      </tr>
      <tr>
        <td><a href="https://site3.com/mysql">Banco de dados MySQL na prática</a></td>
      </tr>
    </table>
  </body>
</html>
';

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);

$rows = $dom->getElementsByTagName("tr");

$resultado = [];

foreach ($rows as $row) {
    $links = $row->getElementsByTagName("a");

    foreach ($links as $a) {
        $texto = trim($a->textContent);
        $href = $a->getAttribute("href");

        if (strlen($texto) > 10 && str_starts_with($href, "http")) {
            $resultado[] = [$texto, $href];
        }
    }
}

$file = fopen("coleta_links.csv", "w");
fputcsv($file, ["titulo", "link"]);

foreach ($resultado as $linha) {
    fputcsv($file, $linha);
}

fclose($file);

echo "ok\n";
