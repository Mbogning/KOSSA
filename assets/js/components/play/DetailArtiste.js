import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import Base from '../../components/play/Base';
import ContentRightMidle from '../../components/play/ContentRightMidle';
import axios from 'axios';
import * as constant from '../Constant';

class DetailArtiste extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tickets: [],
            loading: false,
            nom: '',
            email: '',
            tel: '',
            prix: 0,
            type: null,
            nombre: 1,
            ticketId: null,
            total: 0,
            errors: {
                name: null,
                email: null,
                tel: null,
                nombre: null
            }

        };

        this.handleTicketSubmit = this.handleTicketSubmit.bind(this);
        this.handleShowPaiement = this.handleShowPaiement.bind(this);
    }

    componentDidMount() {

        /*fetch('/' + window.locale + '/event/json_event_tickets/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('tickets...', entries)
                this.setState({
                    tickets: entries,
                    loading: true
                });

            });

         const script = document.createElement("script");          
          script.src = "https://www.wecashup.com/library/MobileMoney.js";
          script.async = true;  
          document.body.appendChild(script);*/
    }

    handleTicketScroll(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('bounceInDown2').removeClass('d-none');
    }


    handlePaiementClick(e) {
        $('#WCUpaymentButton').click();
    }



    handleTicketAction(e, id, prix, type) {

        this.setState({ prix: prix });
        this.setState({ ticketId: id });
        this.setState({ type: type });
        this.setState({ nombre: 1 });

        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');


        if (window.userId > 0 && prix == 0) {//si le user est connecté
            axios.post('/' + window.locale + '/event/json_manage_ticket/' + id, {
                prix: this.state.prix,
            }).then(response => {
                this.setState({ nombre: 1 });

                //console.log('succes....',response.data.message)
                let message = response.data.message;
                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br/><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br/><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }
            );
        } else {
            $('.cont-tik').removeClass('d-none sout').addClass('siut');
        }

    }

    handleShowPaiement(e) {
        //console.log('nianag....................')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }

        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
        //$(this).parent().parent().addClass('d-none b-tkt');
        $('.cont-tik').removeClass('siut').addClass('d-none');
        //on acitve la dialogue du paiement
        $('.cont-pay').removeClass('d-none sout').addClass('siut');
    }

    handleTicketSubmit(e) {

        console.log('je sousmet le form')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }


        axios.post('/' + window.locale + '/event/json_manage_ticket/' + this.state.ticketId, {
            email: this.state.email,
            nom: this.state.nom,
            tel: this.state.tel,
            nombre: this.state.nombre,
            prix: this.state.prix,
        }).then(response => {
            this.setState({ nombre: 1 });

            //console.log('succes....',response.data.message)
            let message = response.data.message;
            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br/><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br/><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }
        );
    }

    handleContentChange(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({ [name]: value });
    }

    validate(name, tel, email, nombre) {
        let token = null;
        let errors = {};


        if (this.state.prix > 0) {//ticket payant
            if (nombre <= 0) {
                errors.nombre = " :Le nombre de tickets doit etre au  moins 1";
                token = "errors";
            }

            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        } else { //ticket gratuit
            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (tel.length === 0) {
                    errors.tel = " :Veuillez renseigner votre téléphone";
                    token = "errors";
                }


                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        }

        this.setState({ errors: errors });
        return token;
    }


    render() {
        const website = `http://${window.location.hostname}:${window.location.port}/`;
        const urlpay = website + window.locale + '/pay/wecashup';
        const logo = website + '/kossa/img/kossa.png';

        const standley = '/kossa/img/enow2.jpg';
        return (
            <Base
                content={
                    <div>
                        <div className="mb-4">
                            <div className="pl-4 pt-4 pr-4 pb-2 white toile-content mt-1">
                                <div className=" mb-3 ml-2 mt-3" >
                                    <div className="">
                                        <div className="content-mus wh-art brd-bl position-relative ovh z-depth-1 d-inline-block" style={{ backgroundImage: `url(${standley})` }}>
                                        </div>
                                        <div className="content-mus wh-art brd-bl position-relative ovh z-depth-1 d-inline-block" style={{ backgroundImage: `url(${standley})` }}>
                                        </div>
                                        <div className="content-mus wh-art brd-bl position-relative ovh z-depth-1 d-inline-block" style={{ backgroundImage: `url(${standley})` }}>
                                        </div>
                                        <div className="mt-3 name-mus w-100 text-center">
                                            <span className="font-weight-bold w-100 text-center h4-responsive lh-12">Stanley enow</span><br />
                                            <span className="text-center text-muted w-100">33 ans</span>
                                        </div>
                                    </div>

                                    <div className="mt-3">
                                        <a href="#album" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">08</span> Album</a>
                                        <a href="#single" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">16</span> Single</a>
                                        <a href="#video" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">12</span> Video</a>
                                        <a href="#audio" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">18</span> Audio</a>
                                        <a href="art-photos.html" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">35</span> Photos</a>
                                        <a href="#genre" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">03</span> Gengre</a>
                                        <a href="art-pub.html" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">25</span> Publication</a>
                                        <a href="art-com.html" className="btn btn-outline-purple btn-rounded btn-sm font-weight-bold"> <span className="badge red mr-2 pt-1 pb-1 pl-2 pr-2">150</span> Commentaire</a>
                                    </div>
                                </div>

                            </div>
                            <div className="p-4 white toile-content mt-2 text-left mb-4 ">
                                <div className="d-inline-block text-center mr-4 mt-2">
                                    <span className="d-inline-block fs-20">125</span><br />
                                    <span className="fs-12 text-muted" >Vues</span>
                                </div><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                                    <i className="far fa-heart fs-20"></i><br />
                                    <span className="fs-12 text-muted" >000 j'aime</span>
                                </a><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                                    <i className="fa fa-thumbs-down fs-20"></i><br />
                                    <span className="fs-12 text-muted" >10 j'aime pas</span>
                                </a><a href="art-com.html" className="d-inline-block text-center mr-4 mt-2 tt">
                                    <i className="far fa-comment fs-20"></i><br />
                                    <span className="fs-12 text-muted" >10 Commentaire</span>
                                </a><a href="#" className="d-inline-block text-center mr-4 mt-2 tt">
                                    <i className="far fa-star fs-20"></i><br />
                                    <span className="fs-12 text-muted" >10 Favoris</span>
                                </a><div className="d-inline-block text-center position-relative zit">
                                    <div className="d-none position-absolute artiste-share bg-play p-2 rounded-pill z-depth-2 " style={{width: '260px !important', top:'10%', left: '-150%'}}>
                                        <div className="">
                                            <a href="" className="btn-social fb-ic mr-3" role="button"><i className="fab fa-lg fa-facebook-f"></i></a><a href="" className="btn-social tw-ic mr-3" role="button"><i className="fab fa-lg fa-twitter"></i></a><a href="" className="btn-social gplus-ic mr-3" role="button"><i className="fab fa-lg fa-google-plus-g"></i></a><a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg fa-linkedin-in"></i></a><a href="" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg  fa-facebook-messenger"></i></a><a href="" className="btn-social ins-ic mr-3" role="button"><i className="fab fa-lg fa-instagram"></i></a><a href="" className="btn-social email-ic" role="button"><i className="far fa-lg fa-envelope"></i></a>
                                        </div>
                                    </div>
                                    <span className="d-inline-block text-center mr-4 mt-2 tt btn-artiste-share tt cur-pointer">
                                        <i className="fa fa-share fs-20"></i><br />
                                        <span className="fs-12 text-muted" >partager</span>
                                    </span>
                                </div>
                            </div>

                            <div className="mb-4" id="genre">
                                <div className="p-3 white  toile-content">
                                    <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold">Genres musicaux de l'artiste</span>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                    <div className="badge black fs-14 mb-3">Music hurbaine</div><br />
                                    <div className="d-inline-block mb-3 mr-2" >
                                        <h4 className="font-weight-bold ls3">#HipHop</h4>
                                    </div>
                                    <div className="d-inline-block mb-3 mr-2" >
                                        <h4 className="font-weight-bold ls3">#RnB</h4>
                                    </div>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                    <div className="badge black fs-14 mb-3">Music locale</div><br />
                                    <div className="d-inline-block mb-3 mr-2" >
                                        <h4 className="font-weight-bold ls3">#Makossa</h4>
                                    </div>
                                    <div className="d-inline-block mb-3 mr-2" >
                                        <h4 className="font-weight-bold ls3">#Bikoutsi</h4>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-4" id="album">
                                <div className="p-3 white  toile-content">
                                    <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold">Albums de l'artiste</span>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                    <div className="a-play-cont position-relative d-inline-block  mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detalbum.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Nabila </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                    <div className="a-play-cont position-relative d-inline-block mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detalbum.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Tenor </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                    <div className="a-play-cont position-relative d-inline-block mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detalbum.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Locko </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-4" id="single">
                                <div className="p-3 white  toile-content">
                                    <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold">Singles de l'artiste</span>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                    <div className="a-play-cont position-relative d-inline-block  mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Nabila </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                    <div className="a-play-cont position-relative d-inline-block mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Tenor </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                    <div className="a-play-cont position-relative d-inline-block mb-3 ml-2">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                            <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                <div className="position-relative time-play" style={{top:'100px'}}>
                                                    <div className="text-center">
                                                        <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                    </div>
                                                    <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Locko </span><div className="text-truncate"><span className="text-red">#</span> Titre du single</div></div>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-4" id="video">
                                <div className="p-3 white  toile-content">
                                    <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold"><i className="fa fa-video "></i> video de l'artiste</span>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content">
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate"> Titre de la video</div></div>
                                                <span className="fs-12 text-muted">Il y'a 12 jours</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate">Titre de la video</div></div>
                                                <span className="fs-12 text-muted">Il y'a 25 jours</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate">Titre de la video</div></div>
                                                <span className="fs-12 text-muted">Il y'a 2 ans</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-4" id="audio">
                                <div className="p-3 white  toile-content">
                                    <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold"><i className="fa fa-play "></i> Audio de l'artiste</span>
                                </div>
                                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate"> Titre de l'audio</div></div>
                                                <span className="fs-12 text-muted">Il y'a 12 jours</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate">Titre de l'audio</div></div>
                                                <span className="fs-12 text-muted">Il y'a 25 jours</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-2" >
                                        <div className="weight-mus">
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{top:'100px'}}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><div className="text-truncate">Titre de l'audio</div></div>
                                                <span className="fs-12 text-muted">Il y'a 2 ans</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                }
                contentRight={
                    <div>
                        <div className="mb-md-5 row"></div>
                        <div className="white p-3 toile-content mb-2">
                            <div className="h5-responsive font-weight-bold mt-3 mb-3 mb-1 text-center text-md-left">
                                DetailArtistes de la semaine.
                            </div>
                            <div className="text-center ">
                                <div className="d-inline-block mb-3 ml-2 mt-3 " >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block" >
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art2 brd-bl position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus2 text-center">
                                            <div className="font-weight-bold name-art text-center">Ténor </div>
                                            <div className="text-center cl-n2 fs-12">150 J'aime"</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <ContentRightMidle />
                    </div>
                }

            >
            </Base>
        );
    }
}

export default DetailArtiste;



















