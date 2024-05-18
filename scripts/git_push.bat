@echo off

echo Enter your commit message:
set /p commit_message=

git add .
git commit -m "%commit_message%"
git push origin main

