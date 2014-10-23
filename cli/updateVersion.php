<?php
// Can be executed on release branch to update the version info.
// A release branch name should look like release/<version>(<optional description>)

try {
    $output = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

    if (!preg_match('/^release\/([0-9]+\.[0-9]+\.[0-9]+)/', $output, $matches)) {
        throw new \Exception('you are currently not on a valid release branch.');
    }

    $version = $matches[1];
    echo 'Version found: ' . $version . PHP_EOL;

    echo 'update config...' . PHP_EOL;

    // read config
    $configFile = __DIR__ . '/../codedocs.json';
    $content = file_get_contents($configFile);
    $json = json_decode($content, true);

    // update version in footer
    $footerLinks = $json['additional']['footerLinks'];
    array_shift($footerLinks);
    $footerLinks = array_merge(['Version ' . $version => null], $footerLinks);
    $json['additional']['footerLinks'] = $footerLinks;

    // write new config
    $content = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents($configFile, $content);

    echo 'update changelog...' . PHP_EOL;

    // update changelog
    $changelogFile = __DIR__ . '/../CHANGELOG.md';
    $changelog = file_get_contents($changelogFile);
    $pos = 13;

    $newChangelog =
        substr($changelog, 0, $pos) .
        '#### Version ' . $version . PHP_EOL .
        PHP_EOL .
        '(no info)' . PHP_EOL .
        PHP_EOL .
        substr($changelog, $pos);
    file_put_contents($changelogFile, $newChangelog);

    echo 'done!' . PHP_EOL;

} catch(\Exception $ex) {
    echo 'Error: ' . $ex->getMessage() . PHP_EOL;
    exit(1);
}
