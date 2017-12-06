#!/bin/sh
#定义目录路径
prefix='/root/shell'

#延时秒数
second=1

pid_name="${prefix}/RedisEmail.pid"

log_name="${prefix}/RedisEmail.log"

run_name="${prefix}/RedisEmail.php"

nohup php ${run_name} >> ${log_name} 2>&1 & echo $! > ${pid_name}

while [ 1 ]; do
    if [ ! -d /proc/`cat ${pid_name}` ]; then
	nohup php ${run_name} >> ${log_name} 2>&1 & echo $! > ${pid_name};
	echo 'new_pid:'`cat ${pid_name} && date '+%Y-%m-%d %H:%M:%S'`
    fi
    sleep ${second}
done