import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import Base from '../../components/play/Base';
import ContentRightMidle from '../../components/play/ContentRightMidle';
import axios from 'axios';

class Top10 extends React.Component {
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
                contentRight={<ContentRightMidle />}
                content={
                    <div>
                        <div class="h3-responsive font-weight-bold mt-1  mb-4">Top - 10</div>
                        <div class="mb-4">
                            <div class="pl-4 pt-4 pr-4 pb-3 white toile-content ">
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class="float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="fas fa-heart fs-18 text-red tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block position-relative ret-plst">
                                                    <i class="fa fa-trash fs-13 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="Rétirer de la playlist"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div>
                                            </span>
                                        </div>

                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">1</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <a href="video.html" class="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="bottom" title="Play"> <i class="fa fa-play "></i></a><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">stanlay enow </span>- <span class="">titre de la vidéo de la chanson</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class=" float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                    <div class=" btn-plst p-0"> <i class="fa fa-plus "></i></div>
                                                    <div class="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>

                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">2</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <a href="video.html" class="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i class="fa fa-play "></i></a><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">Dafne </span>- <span class="">La magie du titre de la vidéo</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                    <div class=" btn-plst p-0"> <i class="fa fa-plus "></i></div>
                                                    <div class="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>

                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">3</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <div class="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i class="fa fa-play "></i></div><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">Nabila </span>- <span class="">Une autre magie du name</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class=" float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                    <div class=" btn-plst p-0"> <i class="fa fa-plus "></i></div>
                                                    <div class="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>

                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">4</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <a href="video.html" class="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i class="fa fa-play "></i></a><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">Tenor </span>- <span class="">Nfian le wey le yaamoooo</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="fas fa-heart fs-18 text-red tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block position-relative ret-plst">
                                                    <i class="fa fa-trash fs-13 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="Rétirer de la playlist"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">5</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <div class="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i class="fa fa-play "></i></div><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">Locko </span>- <span class="">Une autre magie du name</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline-block mb-3 ml-2" >
                                    <div class="weight-mus">
                                        <div class="mb-2">
                                            <span class="float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                            <span class="float-right fs-13 ls3">
                                                <div class="mr-2 d-inline-block position-relative  " >
                                                    <i class="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-toggle="tooltip" data-placement="bottom" title="J'aime"></i>
                                                    <div class="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                </div><div class="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                    <div class=" btn-plst p-0"> <i class="fa fa-plus "></i></div>
                                                    <div class="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>

                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="a-play-cont position-relative d-inline-block">
                                            <div class="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                            <div class="classement position-absolute z-depth-2 white cl-n2 fs-18 font-weight-bold zit p-3">6</div>
                                            <div class="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                <div class="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                <div class="position-absolute tp-lf0 h-100 w-100 text-center">
                                                    <div class="position-relative time-play" style={{top:'100px'}}>
                                                        <div class="text-center">
                                                            <a href="video.html" class="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i class="fa fa-play "></i></a><a href="detsingle.html" class="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i class="fa fa-ellipsis-h "></i></a>
                                                        </div>
                                                        <div class="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 name-mus weight-mus"><span class="font-weight-bold name-art">Artista </span>- <span class="">Le piment dans la sauce</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                }
            >
            </Base>
        );
    }
}

export default Top10;



















