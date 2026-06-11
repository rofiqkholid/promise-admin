# CPU via WMI/CIM (robust, fast, doesn't require administrator/Performance Log permissions, independent of OS language)
$cpu = [Math]::Round((Get-CimInstance -ClassName Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average, 0)

# RAM & Uptime: lightweight CIM query
$mem = Get-CimInstance -ClassName Win32_OperatingSystem -Property TotalVisibleMemorySize, FreePhysicalMemory, LastBootUpTime
$uptime = (Get-Date) - $mem.LastBootUpTime
$uptimeStr = "{0:00}d {1:00}h" -f $uptime.Days, $uptime.Hours

# CPU Speed: live effective frequency via CIM
$proc = Get-CimInstance -ClassName Win32_Processor | Select-Object MaxClockSpeed, CurrentClockSpeed -First 1
$cpuSpeedGHz = [Math]::Round($proc.CurrentClockSpeed / 1000, 2)

@{
    cpu         = $cpu
    totalMem    = $mem.TotalVisibleMemorySize
    freeMem     = $mem.FreePhysicalMemory
    cpuSpeedGHz = $cpuSpeedGHz
    uptime      = $uptimeStr
} | ConvertTo-Json -Compress
