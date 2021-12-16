import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import Base from '../../components/play/Base';
import ContentRightMidle from '../../components/play/ContentRightMidle';
import axios from 'axios';
import {BrowserRouter, NavLink,Link ,Route, Switch} from 'react-router-dom';
import { withNamespaces } from 'react-i18next';
import * as constant from '../Constant';

export default class Artiste extends React.Component {
    constructor(props) {


        super(props);

        console.log('------------  props constructor',props)
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

        document.title = "artiste"

        /*fetch('/' + window.locale + '/event/json_event_tickets/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('tickets...', entries)
                this.setState({
                    tickets: entries,
                    loading: true
                });

            });
*/
       
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

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
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

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
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
                        <div className="h3-responsive float-md-left font-weight-bold mt-1 mb-2 mb-md-0 mr-4">Artiste</div>
                        <div className="text-right mb-4">
                            <form className="t1-form form-search js-search-form position-relative" action="/search" id="global-search">
                                <input className="search-input play-search" type="text" id="search-query" placeholder="Recherchez un artiste" name="recherche" />
                                <span className="search-icon">
                                    <a className="d-inline-block position-absolute" style={{ bottom: '5px', right: '9px' }} >
                                        <i className="fas fa-search" aria-hidden="true"></i>
                                    </a>
                                </span>
                            </form>
                            <div className="">
                                <span className="btn position-relative btn-danger btn-sm p-1 pl-3 pr-3 font-weight-bold btn-cate">Genre: <span className="chx-cate">#Tout</span>
                                    <div className="position-absolute d-none btn-danger p-0 w-100 text-left lst-elm-cate z-depth-2 animated " style={{ top: '99%', left: '0px' }}>
                                        <div className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-uppercase ls3 elm-cate">#Makossa</div>
                                        <div className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-uppercase ls3 elm-cate">#Bikoutsi</div>
                                        <div className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-uppercase ls3 elm-cate">#Hiphop</div>
                                        <div className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-uppercase ls3 elm-cate">#Mix</div>
                                    </div>
                                </span>

                            </div>
                        </div>
                        <div className="mb-4">
                            <div className="pl-4 pt-4 pr-4 pb-2 white toile-content ">
                                <div className="d-inline-block mb-3 ml-2 mt-3" >

                                    <Link className="a-play-cont position-relative d-inline-block" to= {constant.PLAY_URL + '/artist/details'} >
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">Stanley enow </div>
                                            <div className="text-center cl-n2 fs-12">200 J'aime"</div>
                                        </div>
                                    </Link>
                                </div>
                                <div className="d-inline-block mb-3 ml-2 mt-3" >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block ">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">Ténor </div>
                                            <div className="text-center cl-n2 fs-12">150 J'aime"</div>
                                        </div>
                                    </a>
                                </div>
                                <div className="d-inline-block mb-3 ml-2 mt-3" >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block ">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl  position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">Charlote dipanda </div>
                                            <div className="text-center cl-n2 fs-12">300 J'aime"</div>
                                        </div>
                                    </a>
                                </div>
                                <div className="d-inline-block mb-3 ml-2 mt-3" >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block ">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">blanche bailly</div>
                                            <div className="text-center cl-n2 fs-12">200 J'aime"</div>
                                        </div>
                                    </a>
                                </div>
                                <div className="d-inline-block mb-3 ml-2 mt-3" >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block ">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">Locko</div>
                                            <div className="text-center cl-n2 fs-12">150 J'aime"</div>
                                        </div>
                                    </a>
                                </div>
                                <div className="d-inline-block mb-3 ml-2 mt-3" >
                                    <a href="detartiste.html" className="a-play-cont position-relative d-inline-block ">
                                        <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                        <div className="content-mus rounded-pill wh-art brd-bl  position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                            <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                        </div>
                                        <div className="mt-1 name-mus weight-mus text-center">
                                            <div className="font-weight-bold name-art text-center">Nabila </div>
                                            <div className="text-center cl-n2 fs-12">100 J'aime"</div>
                                        </div>
                                    </a>
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
                                Artistes de la semaine.
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



















