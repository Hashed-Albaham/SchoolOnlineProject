@echo off
echo STARTING GITHUB UPLOAD PROCESS...
echo ---------------------------------

:: Check if git is installed
where git >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Git is not installed or not in your PATH.
    echo Please install Git from https://git-scm.com/downloads and try again.
    pause
    exit /b
)

echo [1/5] Initializing Git repository...
git init

echo [2/5] Adding files (this might take a moment)...
git add .

echo [3/5] Committing files...
git commit -m "Final Delivery: ProSkill Dark Luxury Platform"

echo [4/5] Renaming branch to 'main'...
git branch -M main

echo [5/5] Adding remote origin...
:: Removing old origin if exists to avoid errors
git remote remove origin
git remote add origin https://github.com/Hashed-Albaham/SchoolOnlineProject.git

echo.
echo ---------------------------------
echo READY TO PUSH!
echo.
echo Please ensure you have created the empty repository 'SchoolOnlineProject' on your GitHub account:
echo https://github.com/Hashed-Albaham/SchoolOnlineProject
echo.
echo Press any key to start uploading...
pause

git push -u origin main

echo.
echo ---------------------------------
echo DONE! If there were errors, please check your internet connection or GitHub credentials.
pause
