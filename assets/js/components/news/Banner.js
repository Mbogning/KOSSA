import React from 'react';

class Banner extends React.Component {
  
    render() {
        const image = '/kossa/img/artist.jpg';
        return (
            <div className="cl-b3 one brd-bt-news pt-5 mb-3" style={{ backgroundImage: `url(${image})` }}>
                <div className="position-relative">
                    <div className="container">
                        <div className="row ">
                            <div className="col-12 intro-size mt-5 ml-3 mr-3">
                                <div className="font-weight-bold intro-presentation ">ça ce passe ici et null part alleur! </div>
                            </div>
                            <div className="col-12 pt-4 animated flipInX c05s animated.slower ml-3 mr-3">
                                <div className="h3-responsive intro-presentation font-weight-light" style={{lineHeight: 'normal'}}>Tenez vous informer des actualités sur le showbiz camerounais <br />partous dans le monde. </div>
                            </div>
                        </div>
                        <div className="row text-right mt-5 mb-4 " >
                            <div className="col-12 text-right mb-3 font-weight-bold fs-13 ">Partagez cette page sur</div>
                            <div className="col-12 text-right animated fadeInDown c05s mb-4">
                                <a href="" className="btn-social fb-ic mr-3" role="button"><i className="fab fa-lg fa-facebook-f"></i></a>
                                <a href="" className="btn-social tw-ic mr-3" role="button"><i className="fab fa-lg fa-twitter"></i></a>
                                <a href="" className="btn-social gplus-ic mr-3" role="button"><i className="fab fa-lg fa-google-plus-g"></i></a>
                                <a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg fa-linkedin-in"></i></a>
                                <a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg  fa-facebook-messenger"></i></a>
                                <a href="" className="btn-social ins-ic mr-3" role="button"><i className="fab fa-lg fa-instagram"></i></a>
                                <a href="" className="btn-social email-ic" role="button"><i className="far fa-lg fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Banner;


















