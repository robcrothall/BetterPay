# Git / GitHub setup for v4

1. Initialize git in the v4 folder (if not already):

```bash
cd "C:\Users\<you>\OneDrive\Documents\Dev\BetterPay\v4"
git init
git remote add origin https://github.com/robcrothall/BetterPay.git
git checkout -b main
git add .
git commit -m "Initial v4 skeleton"
git push -u origin main
```

2. Ensure `inc/constants.php` is not committed (it's listed in `.gitignore`).

3. To link an existing local repository to GitHub, follow the `git remote add` and `git push` steps above, or use GitHub Desktop / VS Code Source Control integration.
