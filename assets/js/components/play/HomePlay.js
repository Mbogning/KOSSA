import React, { InvalidEvent } from 'react';
import Top5 from '../../components/Top5';
import Banner from '../../components/play/Banner';
import MenuLeft from '../../components/play/MenuLeft';
import Base from '../../components/play/Base';
import ContentRightMidle from '../../components/play/ContentRightMidle';
import axios from 'axios';
import { withNamespaces, Trans } from 'react-i18next';



class HomePlay extends React.Component {
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
        const script = document.createElement("script");
        script.src = "/kossa/js/custom.js";
        script.async = true;
        document.body.appendChild(script);
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
                banner={<Banner />}
                contentRight={<ContentRightMidle />}
                content={
                    <div>

            

                        <div className="h3-responsive float-left font-weight-bold mt-1 mb-2 mb-md-0">Kossa play: music</div>
                        <div className="text-right  mb-4">
                            <div className="">
                                <span type="button" className="btn position-relative btn-danger btn-sm p-1 pl-3 pr-3 font-weight-bold btn-cate">Genre: <span className="chx-cate">#Tout</span>
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
                            <div className="p-3 white  toile-content">
                                <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold">Ma Playlist</span>
                                <span className="float-right"> <a href="" className="font-weight-bold  fs-12 btn-sm tt">Afficher tout</a></span>
                            </div>
                            <div className="pl-4 pt-4 pr-4 pb-3 white toile-content  position-relative" style={{ height: 'fit-content' }}>
                                <div className="play-playlist">
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className="float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="fas fa-heart fs-18 text-red tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block position-relative ret-plst">
                                                        <i className="fa fa-trash fs-13 tt tolltip-cnt cur-pointer" data-ttle="Rétirer de la playlist"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Stanley enow </span>- <span className="">Dans la ville des héé-père</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Dafne </span>- <span className="">La magie du titre de la vidéo</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Nabila </span>- <span className="">Une autre magie du name</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Nabila </span>- <span className="">Une autre magie du name</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Nabila </span>- <span className="">Une autre magie du name</span></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div className="prev-playlist position-absolute drt-sld white pl-3 pr-3 pt-1 pb-1 trs-03 d-inline-block cur-pointer z-depth-1" style={{ top: '45%', left: '0%' }}>
                                    <i className="fa fa-arrow-left fs-13 "></i>
                                </div>
                                <div className="next-playlist position-absolute drt-sld white pl-3 pr-3 pt-1 pb-1 trs-03 d-inline-block cur-pointer z-depth-1" style={{ top: '45%', right: '0%' }}>
                                    <i className="fa fa-arrow-right fs-13 "></i>
                                </div>
                            </div>
                        </div>
                        <div className="mb-4">
                            <div className="p-3 white  toile-content">
                                <span className="text-md-left d-inline-block ls3 h5-responsive font-weight-bold">Makossa</span>
                                <span className="float-right"> <a href="" className="font-weight-bold  fs-12 btn-sm tt">Afficher tout</a></span>
                            </div>
                            <div className="pl-4 pt-4 pr-4 pb-3 white toile-content position-relative" style={{ height: 'fit-content' }}>
                                <div className="play-makossa">
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className="float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Artista </span>- <span className="">Le piment dans la sauce</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge indigo ls3 pl-2 pr-2 ">Video</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="far fa-heart fs-18 tt tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <a href="video.html" className="d-inline-block btn-in-img tt cur-pointer cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></a><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Tenor </span>- <span className="">Nfian le wey le yaamo</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-inline-block mb-3 ml-3" >
                                        <div className="weight-mus">
                                            <div className="mb-2">
                                                <span className=" float-left d-inline-block badge black ls3 pl-2 pr-2 ">Audio</span>
                                                <span className="float-right fs-13 ls3">
                                                    <div className="mr-2 d-inline-block position-relative  " >
                                                        <i className="fas fa-heart fs-18 text-red tolltip-cnt cur-pointer" data-ttle="J'aime"></i>
                                                        <div className="position-absolute zit p-2 tolltip ls3 d-none "></div>
                                                    </div><div className="d-inline-block  cur-pointer position-relative" data-toggle="tooltip" data-placement="bottom" title="ajouter à la playlist">
                                                        <div className=" btn-plst p-0"> <i className="fa fa-plus "></i></div>
                                                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{ right: '0px' }}>

                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                            <div className="a-play-cont position-relative d-inline-block">
                                                <div className="position-absolute tp-lf0 h-100 w-100 hov-elm"></div>
                                                <div className="content-mus weight-mus toile-content position-relative ovh" style={{ backgroundImage: `url(${standley})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                    <div className="position-absolute tp-lf0 h-100 w-100 text-center">
                                                        <div className="position-relative time-play" style={{ top: '100px' }}>
                                                            <div className="text-center">
                                                                <div className="d-inline-block btn-in-img tt cur-pointer bnt-plyay-mus cl-n2 p-3 z-depth-1 mr-1" data-toggle="tooltip" data-placement="top" title="Play"> <i className="fa fa-play "></i></div><a href="detsingle.html" className="d-inline-block btn-in-img tt cl-n2 p-3 ml-1 z-depth-1" data-toggle="tooltip" data-placement="top" title="Détails"> <i className="fa fa-ellipsis-h "></i></a>
                                                            </div>
                                                            <div className="text-center d-inline-block mt-2 pr-2 pl-2 ls3 black cl-b3 fs-13 cur-def">03:33</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus"><span className="font-weight-bold name-art">Locko </span>- <span className="">Une autre magie du name</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="prev-mako position-absolute drt-sld white pl-3 pr-3 pt-1 pb-1 trs-03 d-inline-block cur-pointer z-depth-1" style={{ top: '45%', left: '0%' }}>
                                    <i className="fa fa-arrow-left fs-13 "></i>
                                </div>
                                <div className="next-mako position-absolute drt-sld white pl-3 pr-3 pt-1 pb-1 trs-03 d-inline-block cur-pointer z-depth-1" style={{ top: '45%', right: '0%' }}>
                                    <i className="fa fa-arrow-right fs-13 "></i>
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

export default HomePlay;




















