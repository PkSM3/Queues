import numpy as np


from flask import Flask
from flask import request
#import simplejson as json
import json
app = Flask(__name__)

def gamma(a,b,N):
	data = np.random.gamma(a, b, N)
	return data.tolist()
	

def beta(a,b,N):
	data = np.random.beta(a,b,N)
	return data.tolist()

@app.route("/getJSON")
def main():
	dist = request.args['dist']
	a = float(request.args['a'])
	b = float(request.args['b'])
	N = int(request.args['N'])

	print "here"
	out=""
	if dist=="gamma": out=gamma(a,b,N)
	if dist=="beta": out=beta(a,b,N)
	return json.dumps(out)


if __name__ == "__main__":
        app.run(port=8080)













#data = np.random.normal(size=10000)*10000

#plt.figure(0)
#plt.hist(data, bins=np.arange(data.min(), data.max(), 1000))

#plt.figure(1)
#hist1 = np.histogram(data, bins=np.arange(data.min(), data.max(), 1000))
#plt.bar(hist1[1][:-1], hist1[0], width=1000)

#plt.figure(2)
#hist2 = np.histogram(data, bins=np.arange(data.min(), data.max(), 200))
#mask = (hist2[1][:-1] < 20000) * (hist2[1][:-1] > 0)
#plt.bar(hist2[1][mask], hist2[0][mask], width=200)

#plt.show()
