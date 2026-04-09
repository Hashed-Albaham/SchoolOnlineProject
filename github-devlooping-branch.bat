@echo off
echo STARTING GITHUB UPLOAD PROCESS...
echo ---------------------------------

:: Get current date and time for unique commit message
for /f "tokens=1-4 delims=/ " %%a in ('date /t') do set mydate=%%c-%%b-%%a
for /f "tokens=1-2 delims=: " %%a in ('time /t') do set mytime=%%a-%%b

:: Check if git is installed
where git >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Git is not installed or not in your PATH.
    echo Please install Git from https://git-scm.com/downloads and try again.
    pause
    exit /b
)

:: Check if already a git repo
if exist ".git" (
    echo [INFO] Git repository already exists...
) else (
    echo [1/5] Initializing Git repository...
    git init
)

echo [2/5] Preparing branch "apdatePyments"...
:: Switch to or create the new branch
git checkout -b apdatePyments 2>nul || git checkout apdatePyments

echo [3/5] Adding files (this might take a moment)...
:: 🛡️ SECURITY: Untrack sensitive files if they were accidentally added before
git rm --cached .env >nul 2>nul
git rm --cached database\*.sqlite >nul 2>nul
git rm --cached *.sqlite >nul 2>nul
git rm --cached *.log >nul 2>nul

git add .

echo [4/5] Committing files with timestamp...
set COMMIT_MSG=Update: SchoolOnlineProject - %mydate% %mytime%
echo Commit message: %COMMIT_MSG%
git commit -m "%COMMIT_MSG%"

:: Ensure remote is set
git remote remove origin 2>nul
git remote add origin https://github.com/Hashed-Albaham/SchoolOnlineProject.git

echo.
echo ---------------------------------
echo READY TO FORCE PUSH!
echo.
echo Repository: https://github.com/Hashed-Albaham/SchoolOnlineProject
echo Branch: apdatePyments
echo [WARNING] This will forcefully push your local code, overwriting the remote branch if it exists.
echo.
echo Press any key to start uploading...
pause

echo [5/5] Force Pushing to GitHub...
git push -u origin apdatePyments --force

echo.
echo ---------------------------------
echo DONE! Your code has been forcefully uploaded to the "apdatePyments" branch.
echo All previous versions on this branch (if any) have been overwritten.
echo.
echo To view history: git log --oneline
pause