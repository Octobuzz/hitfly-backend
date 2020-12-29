import wave
import numpy as np
import math
import json
import sys

#https://habr.com/ru/post/113239/
types = {
    1: np.int8,
    2: np.int16,
    4: np.int32
}
wav = wave.open(sys.argv[1], mode="r")
(
    nchannels
    , sampwidth
    , framerate
    , nframes
    , comptype #
    , compname #
) = wav.getparams()

duration = nframes / framerate
k = int(nframes/duration*3)
peak = 256 ** sampwidth / 2

content = wav.readframes(nframes)
samples = np.fromstring(content, dtype=types[sampwidth])


channel = samples[1::nchannels]
channel = abs(channel[0::k])
my_list = []
for i in channel:
    if(i != 0):
        my_list.append(int(abs(20 * math.log10(abs(int(i)) / float(peak)))))
    else:
        my_list.append(0)
print(json.dumps(my_list))
