$ErrorActionPreference = 'Stop'
for ($attempt = 0; $attempt -lt 30; $attempt++) {
    try {
        $response = Invoke-WebRequest 'http://127.0.0.1:8000/login' -UseBasicParsing -TimeoutSec 1
        if ($response.StatusCode -eq 200) {
            Start-Process 'http://127.0.0.1:8000'
            exit 0
        }
    } catch {
        Start-Sleep -Milliseconds 500
    }
}
