# po

## これは何？

`bin/cake i18n` コマンドで生成されて設置された `src/Locale/ja/default.po` をプロジェクト終了まで育てていくために便利なコマンドを提供するCakePHP3のプラグインです

### インストール

```sh
$ composer require k1low/po
$ 
```

## コマンド

### po merge

```sh
$ bin/cake po.po merge
```

`src/Locale/ja/default.po` を設置した後に、ソースコード内に `__()` が追加されることがあると思います。

その時に `bin/cake i18n` で生成された `src/Locale/default.pot` と `src/Locale/ja/default.po` をいい感じにマージするコマンドです。

#### マージ戦略

1. `src/Locale/ja/default.po` 内の記述済 `msgstr` は上書きしない
2. `src/Locale/ja/default.po` にない新規 `msgid` があったら追加する
2. `src/Locale/ja/default.po` 内の `msgstr` が `""` だった場合は `src/Locale/default.pot` から上書きする

### po schema

```sh
$ bin/cake po.po schema
```

CakePHPでは、FormHelperのラベルなど、自動で `__()` を生成しているため `bin/cake i18n` のパースに引っかからないものが多くあります。

そのほとんどはデータベースのスキーマ情報と `Cake\Utility\Inflector` クラスから生成されるものです。

その `msgstr` 記述のために、いちいち `msgid` から記述するのは面倒です。

その時にデータベースのスキーマ情報からいい感じに `src/Locale/schema.pot` を生成してくれるコマンドです。

さらに、スキーマ情報にCOMMENTが記載されていれば、それを `msgstr` に追記してくれます。

mergeコマンドと併用することで `src/Locale/ja/default.po` を育てることができます。
