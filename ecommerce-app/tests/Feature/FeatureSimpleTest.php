<?php

namespace Tests\Feature;

use Tests\TestCase;

class FeatureSimpleTest extends TestCase
{
    /**
     * 文法エラーチェックのテスト
     *
     * @return void
     */
    public function testForSyntaxError()
    {
        // チェックしたいファイルのパス
        $filesToCheck = [
            base_path('routes/web.php'),
            // 追加でファイルを指定できます
        ];

        foreach ($filesToCheck as $file) {
            $this->assertFileExists($file, "ファイルが見つかりません: {$file}");

            // ファイルに文法エラーがないか確認
            $output = shell_exec("php -l {$file}");  // shell_execを使用

            // 結果がエラーなら出力する
            $this->assertStringNotContainsString('Parse error', $output, "PHP構文エラー: {$file}\n" . $output);
        }
    }
}
