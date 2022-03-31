@ECHO OFF
if exist C:\Users\VaxPex\Downloads\Server\plugins\WoolWars (
    rmdir /S /Q C:\Users\VaxPex\Downloads\Server\plugins\WoolWars
)
mkdir C:\Users\VaxPex\Downloads\Server\plugins\WoolWars
xcopy C:\Users\VaxPex\Downloads\plugins\WoolWars C:\Users\VaxPex\Downloads\Server\plugins\WoolWars /E /H /C /I