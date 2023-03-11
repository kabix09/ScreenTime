# HTTP Client from JetBrains
JetBrains Documentation: https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html

JetBrains tutorial video: https://www.youtube.com/watch?v=VMUaOZ6kvJ0

---
## Manual
To edit variables in file [http-client.env.json](http-client.env.json) you must firstly copy him to:

```shell
cp http-client.env.json http-client.private.env.json
```

Files with name `*.private.env.json` are readed by PHPStorma and more importantly they aren't commited by git.
