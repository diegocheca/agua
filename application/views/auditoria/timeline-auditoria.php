
<style type="text/css">

	body {
	  font-family: 'Roboto';
	  font-size: 17px;
	  font-weight: 400;
	  background-color: #eee;
	}

	h1 {
	  font-size: 200%;
	  text-transform: uppercase;
	  letter-spacing: 3px;
	  font-weight: 400;
	}

	header p {
	  font-family: 'Allura';
	  color: rgba(255, 255, 255, 0.2);
	  margin-bottom: 0;
	  font-size: 60px;
	  margin-top: -30px;
	}

	.timeline {
	  position: relative;
	}
	.timeline::before {
	  content: '';
	  background: #C5CAE9;
	  width: 5px;
	  height: 95%;
	  position: absolute;
	  left: 50%;
	  transform: translateX(-50%);
	}

	.timeline-item {
	  width: 100%;
	  margin-bottom: 70px;
	}
	.timeline-item:nth-child(even) .timeline-content {
	  float: right;
	  padding: 40px 30px 10px 30px;
	}
	.timeline-item:nth-child(even) .timeline-content .date {
	  right: auto;
	  left: 0;
	}
	.timeline-item:nth-child(even) .timeline-content::after {
	  content: '';
	  position: absolute;
	  border-style: solid;
	  width: 0;
	  height: 0;
	  top: 30px;
	  left: -15px;
	  border-width: 10px 15px 10px 0;
	  border-color: transparent #f5f5f5 transparent transparent;
	}
	.timeline-item::after {
	  content: '';
	  display: block;
	  clear: both;
	}

	.timeline-content {
	  position: relative;
	  width: 45%;
	  padding: 10px 30px;
	  border-radius: 4px;
	  background: #f5f5f5;
	  box-shadow: 0 20px 25px -15px rgba(0, 0, 0, 0.3);
	}
	.timeline-content::after {
	  content: '';
	  position: absolute;
	  border-style: solid;
	  width: 0;
	  height: 0;
	  top: 30px;
	  right: -15px;
	  border-width: 10px 0 10px 15px;
	  border-color: transparent transparent transparent #f5f5f5;
	}

	.timeline-img {
	  width: 30px;
	  height: 30px;
	  background: #3F51B5;
	  border-radius: 50%;
	  position: absolute;
	  left: 50%;
	  margin-top: 25px;
	  margin-left: -15px;
	}

	/*a {
	  background: #3F51B5;
	  color: #FFFFFF;
	  padding: 8px 20px;
	  text-transform: uppercase;
	  font-size: 14px;
	  margin-bottom: 20px;
	  margin-top: 10px;
	  display: inline-block;
	  border-radius: 2px;
	  box-shadow: 0 1px 3px -1px rgba(0, 0, 0, 0.6);
	}
	a:hover, a:active, a:focus {
	  background: #32408f;
	  color: #FFFFFF;
	  text-decoration: none;
	}
	*/
	.timeline-card {
	  padding: 0 !important;
	}
	.timeline-card p {
	  padding: 0 20px;
	}
	.timeline-card a {
	  margin-left: 20px;
	}

	.timeline-item .timeline-img-header {
	  background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.4)), url("https://picsum.photos/1000/800/?random") center center no-repeat;
	  background-size: cover;
	}

	.timeline-img-header {
	  height: 200px;
	  position: relative;
	  margin-bottom: 20px;
	}
	.timeline-img-header h2 {
	  color: #FFFFFF;
	  position: absolute;
	  bottom: 5px;
	  left: 20px;
	}

	blockquote {
	  margin-top: 30px;
	  color: #757575;
	  border-left-color: #3F51B5;
	  padding: 0 20px;
	}

	.date {
	  background: #FF4081;
	  display: inline-block;
	  color: #FFFFFF;
	  padding: 10px;
	  position: absolute;
	  top: 0;
	  right: 0;
	}

	@media screen and (max-width: 768px) {
	  .timeline::before {
	    left: 50px;
	  }
	  .timeline .timeline-img {
	    left: 50px;
	  }
	  .timeline .timeline-content {
	    max-width: 100%;
	    width: auto;
	    margin-left: 70px;
	  }
	  .timeline .timeline-item:nth-child(even) .timeline-content {
	    float: none;
	  }
	  .timeline .timeline-item:nth-child(odd) .timeline-content::after {
	    content: '';
	    position: absolute;
	    border-style: solid;
	    width: 0;
	    height: 0;
	    top: 30px;
	    left: -15px;
	    border-width: 10px 15px 10px 0;
	    border-color: transparent #f5f5f5 transparent transparent;
	  }
	}


</style>