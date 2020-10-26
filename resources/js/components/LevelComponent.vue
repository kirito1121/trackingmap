<template>
  <v-card>
    <v-alert dense outlined color="#3490dc" type="info">
      <strong> Level </strong>: <strong>Nhập level đầu và cuối</strong> ví dụ
      <strong>1,2</strong> Hoặc nhập một level bất kì và không nhập AppVersion
      để tìm hết version của level đó
    </v-alert>
    <v-alert dense outlined color="#3490dc" type="info">
      <strong> Level type</strong>: <strong> Nhập số </strong> saga là
      <strong> 0 </strong> rush là <strong> 1 </strong> adventure là
      <strong> 2 </strong>
    </v-alert>
    <v-alert dense outlined color="#3490dc" type="info">
      <strong> Sub Level </strong>:
      <strong> Nhập số, tìm chung với Rush or adventure nếu cần </strong> tìm
      với saga sẽ <strong> không có kết quả </strong>
    </v-alert>
    <v-alert dense outlined color="#3490dc" type="info">
      <strong> Version </strong>: <strong> Nhập số </strong> là version của
      level
    </v-alert>
    <v-alert dense outlined color="#3490dc" type="info">
      <strong> App Version </strong>: <strong> Chọn version cần tìm </strong> là
      version của game
    </v-alert>

    <v-card-title>
      <v-row justify="space-between">
        <v-col cols="12" md="4">
          <v-text-field
            hide-details
            label="Level"
            single-line
            v-model="searchData.level"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            hide-details
            label="Level Type"
            single-line
            v-model="searchData.levelType"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            hide-details
            label="Sub level"
            single-line
            v-model="searchData.sublevel"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            hide-details
            label="Version"
            single-line
            v-model="searchData.version"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-select
            :items="versions"
            multiple
            chips
            v-model="searchData.appVersion"
            :menu-props="{ top: true, offsetY: true }"
            label="App Version"
          ></v-select>
        </v-col>
        <v-col cols="12" md="4">
          <div class="my-2">
            <v-btn @click="searchLevel()" color="#3490dc" dark small
              >Search</v-btn
            >
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="my-2">
            <v-btn
              @click="back"
              color="#3490dc"
              dark
              small
              :disabled="!searchData.level"
              >Back</v-btn
            >
            <v-btn @click="next" color="#3490dc" dark small>Next</v-btn>
          </div>
        </v-col>
      </v-row>
    </v-card-title>
    <div class="container">
      <v-data-table
        :expanded="expanded"
        :headers="headers"
        :items="data"
        :itemsPerPage="Number(100)"
        :loading="true"
        @click:row="clicked"
        class="elevation-1"
        hide-default-footer
        item-key="name"
        loading-text="Loading... Please wait"
        show-expand
      >

        <template v-slot:expanded-item="{ item }">
          <td :colspan="4" class="text-start p-3">
            <v-card class="p-2" v-if="item['i']">
              <v-card-text>
                <v-row class="d-flex">
                  <v-col class="ml-2" style="max-width: 650px">
                    <v-row
                      style="margin-right: -80px"
                      :key="'row' + kr"
                      class="ml-2"
                      v-for="(kr, i) in Number(item['g'])"
                    >
                      <v-col
                        :cols="item['g']"
                        :key="'col' + ch"
                        md="1"
                        style="padding: 0"
                        v-for="(ch, k) in Number(item['h'])"
                      >
                        <div v-if="item['j']">
                          <div
                            v-for="(train, keyTrain) in convertImageTrain(
                              item['g'],
                              item['h'],
                              i,
                              k,
                              item['j'],
                              false
                            )"
                            :key="'train' + keyTrain"
                          >
                            <v-img
                              v-if="train.coordinates === i + '_' + k"
                              style="position: absolute; z-index: 2"
                              :src="train.src"
                            ></v-img>
                          </div>
                        </div>

                        <div v-if="item['l']">
                          <div
                            v-for="(tram, keytram) in item['l']"
                            :key="'tram' + keytram"
                          >
                            <div
                              v-for="(train, keyTrain) in convertImageTrain(
                                item['g'],
                                item['h'],
                                i,
                                k,
                                tram,
                                true
                              )"
                              :key="'train' + keyTrain"
                            >
                              <v-img
                                v-if="train.coordinates === i + '_' + k"
                                style="position: absolute; z-index: 2"
                                :src="train.src"
                              ></v-img>
                            </div>
                          </div>
                        </div>

                        <v-img
                          v-for="(item, key) in convertImage(i, k, item['cp'])"
                          :style="item.style"
                          :src="item.src"
                          :key="key + '_' + i + '_' + k"
                        ></v-img>
                        <v-img
                          src="/storage/images/Cell1.png"
                          v-if="(i + k) % 2 == 0"
                        ></v-img>
                        <v-img
                          src="/storage/images/Cell2.png"
                          v-if="(i + k) % 2 != 0"
                        ></v-img>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-btn @click="showDialog(item['id'])">Delete this level</v-btn>
              </v-card-actions>
            </v-card>
          </td>
          <td :colspan="4" class="text-start p-3">
              <v-card class="p-2" v-if="item['i']">
                  <v-card-text>
                      <v-row class="d-flex">
                          <v-col class="ml-2" style="max-width: 650px" >

                          </v-col>
                      </v-row>
                  </v-card-text>
              </v-card>
          </td>
        </template>
      </v-data-table>
    </div>
    <v-dialog v-model="dialog" width="500" persistent>
      <v-card>
        <v-card-title class="headline grey lighten-2">Comfirm</v-card-title>
        <v-card-text>Chắc chưa ban ê!</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="closeDialog()">Cancel</v-btn>
          <v-btn color="primary" text @click="destroy(itemId)">I accept</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import LineChart from "./LineChart.js";
export default {
  components: {
    LineChart,
  },
  data() {
    return {
      dialog: false,
      search: "",
      expanded: ["Map"],
      singleExpand: false,
      headers: [
        {
          text: "Level",
          value: "level",
        },
        {
          text: "Sub Level",
          value: "sublevel",
        },
        {
          text: "Map Level",
          value: "mapLevel",
        },
        {
          text: "Target",
          value: "target",
        },
        {
          text: "Count Target",
          value: "countTarget",
        },
        {
          text: "Move",
          value: "move",
        },
        {
          text: "Score",
          value: "score",
        },
        {
          text: "Hard Level",
          value: "hardLevel",
        },
        {
          text: "Version",
          value: "version",
        },
        {
          text: "App Version",
          value: "appVersion",
        },
        {
          text: "Booster(R_T_P)",
          value: "gara",
        },
        {
          text: "Mana",
          value: "mana",
        },
      ],
      data: [{}],
      searchData: {
        version: null,
        level: null,
        levelType: null,
        appVersion: null,
        sublevel: null,
      },
      a: 0,
      entity: null,
      versions: null,
      arraycoordinates: [],
      itemId: null,
    };
  },


  methods: {
    getDataLevel(params) {
      axios
        .get("api/viewLevel", {
          params: params,
        })
        .then((response) => {
          this.data = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getDataPlay(version,level,subLevel,levelType){
        axios
        .get("api/level/playData", {
          params: {
              appVersion: version,
              level: level,
              subLevel: subLevel,
              levelType: levelType
          },
        })
        .then((response) => {
          this.dataPlay = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    searchLevel() {
      this.getDataLevel(this.searchData);
    },
    getEntity() {
      axios
        .get("api/entity")
        .then((response) => {
          this.entity = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getVersions() {
      axios
        .get("api/versions")
        .then((response) => {
          this.versions = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    clicked(value) {
      this.expanded.push(value);
    },
    show(value) {},

    convertImage(i, k, items) {
      let item = items[i + "_" + k];
      let dataImage = [];
      if (item == "x") {
        dataImage.push({
          src: "storage/images/X.png",
          style: "position:absolute;z-index:1",
        });
        return dataImage;
      }

      let arrayItem = item.split(",");
      let dataArray = [];
      arrayItem.forEach((element) => {
        let arr = element.split("_");
        dataArray.push(arr);
      });

      dataArray.forEach((array) => {
        let txt = "";
        let style = "";
        for (let index = 0; index < array.length; index++) {
          if (index == 0) {
            txt += this.findItem(array[index], this.entity.entityType);
            if (array[index] == 42) {
              txt = "";
            }
          }
          txt = txt == "Helipad" ? "Helicopter" : txt;
          if (["Grass", "Helicopter", "Snow"].indexOf(txt) > -1) {
            style = "position:absolute;z-index:1";
            if (index == 1) {
              txt += false;
            }
            if (index == 2) {
              txt += false;
            }
            if (index == 3) {
              txt += false;
            }
          } else if (txt == "TrafficCone") {
            style = "position:absolute ; z-index:3";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.levels);
            }
            if (index && index == 2) {
              txt += this.findItem(array[index], this.entity.entityColor);
            }
          } else if (txt == "Locker") {
            style = "position:absolute ; z-index:3; margin-top: 15px;";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.levels);
            }
          } else if (txt == "ColorChanger") {
            style = "position:absolute;z-index:1";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.entityColor);
            }
          } else if (txt == "Bollard") {
            style = "position:absolute ; z-index:3";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.bollard);
            }
          } else if (txt.search("Barrier") > -1) {
            style = "position:absolute ; z-index:3";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.direction);
            }
          } else if (txt.search("SpaceshipPad") > -1) {
            style = "position:absolute ; z-index:1";
            if (index == 1) {
              txt +=
                this.findItem(array[index], this.entity.directionX) ??
                "vertical";
            }
          } else if (txt.search("Wreck") > -1) {
            style = "position:absolute ; z-index:3;";
          } else if (txt.search("FoldingBarrier") > -1) {
            style = "position:absolute ; z-index:3";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.direction);
            }
          } else if (txt.search("Tunnel") > -1) {
            style = "position:absolute ; z-index:3";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.direction);
            }
          } else if (txt.search("Bench") > -1) {
            style = "position:absolute ; z-index:3;";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.direction);
            }
          } else {
            style = "position:absolute ; z-index:3;bottom:0px";
            if (index == 1) {
              txt += this.findItem(array[index], this.entity.direction);
            }
            if (index == 2) {
              txt += this.findItem(array[index], this.entity.entityColor);
            }
          }
        }
        if (txt !== "undefined") {
          if (txt == "Helicopter" || txt == "Fountain") {
            style = "position:absolute; z-index:1; max-width:200%";
          }
          if (txt == "BenchUp" || txt == "BenchDown") {
            style = "position:absolute; z-index:3; max-width:300%";
          }
          if (txt.search("FoldingBarrierUp") > -1) {
            style += "; bottom:0px";
          }
          if (
            txt.search("FoldingBarrierLeft") > -1 ||
            txt.search("FoldingBarrierRight") > -1
          ) {
            style += ";max-width: 300%";
          }
          if (txt.search("FoldingBarrierLeft") > -1) {
            style += ";max-width: 300%;right: 0px ";
          }
          if (txt.search("SpaceshipPadhorizontal") > -1) {
            style += "; max-width: 290%;bottom: -75px;";
          }
          if (txt.search("SpaceshipPadvertical") > -1) {
            style += "; max-width: 200%";
          }
          if (txt.search("SpaceshipPadvertical") > -1) {
            //   style += "; max-width: 290%;bottom: -75px;"
          }

          txt = txt.replace("undefined", "");
          dataImage.push({
            src: "storage/images/" + txt + ".png",
            style: style,
          });
        }
      });
      //   console.log(dataImage);
      return dataImage;
    },

    convertImageTrain(g, h, i, k, itemtrain, tram) {
      let arrTrain = [];
      for (let index = 0; index < itemtrain.length; index++) {
        if (itemtrain.length - 2 > index) {
          let arr1 = itemtrain[index].split("_");
          let arr2 = itemtrain[
            index + 1 == itemtrain.length ? index : index + 1
          ].split("_");
          let arr3 = itemtrain[
            index + 2 == itemtrain.length ? index : index + 2
          ].split("_");
          let txt = "";
          if (arr1[0] == arr2[0] && arr1[1] < arr2[1]) {
            if (arr2[0] > arr3[0]) {
              txt = "|DownLeft";
            }
            if (arr2[0] < arr3[0]) {
              txt = "|UpLeft";
            }
          }
          if (arr1[0] == arr2[0] && arr1[1] > arr2[1]) {
            if (arr2[0] > arr3[0]) {
              txt = "|DownRight";
            }
            if (arr2[0] < arr3[0]) {
              txt = "|UpRight";
            }
          }
          if (arr1[0] > arr2[0] && arr1[1] == arr2[1]) {
            if (arr2[1] > arr3[1]) {
              txt = "|UpLeft"; // right down
            }
            if (arr2[1] < arr3[1]) {
              txt = "|UpRight"; // left down
            }
          }
          if (arr1[0] < arr2[0] && arr1[1] == arr2[1]) {
            if (arr2[1] > arr3[1]) {
              txt = "|DownLeft"; // right up
            }
            if (arr2[1] < arr3[1]) {
              txt = "|DownRight"; // = Left up
            }
          }
          arrTrain.push(itemtrain[index + 1] + "" + txt);
        }
      }

      for (let index = 0; index < itemtrain.length; index++) {
        let arr1 = itemtrain[index].split("_");
        let arr2 = itemtrain[
          index + 1 == itemtrain.length ? index : index + 1
        ].split("_");
        let temp = null;
        if (
          Number.parseInt(arr1[0]) == Number.parseInt(arr2[0]) &&
          Number.parseInt(arr1[1]) < Number.parseInt(arr2[1])
        ) {
          // ngang trai
          for (
            let jindex = 1;
            jindex < Number.parseInt(arr2[1]) - Number.parseInt(arr1[1]);
            jindex++
          ) {
            temp =
              Number.parseInt(arr1[0]) +
              "_" +
              (Number.parseInt(arr1[1]) + Number.parseInt(jindex)) +
              "|Horizontal";
            arrTrain.push(temp);
          }
        } else if (
          Number.parseInt(arr1[0]) == Number.parseInt(arr2[0]) &&
          Number.parseInt(arr1[1]) > Number.parseInt(arr2[1])
        ) {
          // ngang phai
          for (
            let jindex = 1;
            jindex < Number.parseInt(arr1[1]) - Number.parseInt(arr2[1]);
            jindex++
          ) {
            temp =
              Number.parseInt(arr1[0]) +
              "_" +
              (Number.parseInt(arr1[1]) - Number.parseInt(jindex)) +
              "|Horizontal";
            arrTrain.push(temp);
          }
        } else if (
          Number.parseInt(arr1[1]) == Number.parseInt(arr2[1]) &&
          Number.parseInt(arr1[0]) > Number.parseInt(arr2[0])
        ) {
          // doc len
          for (
            let jindex = 1;
            jindex < Number.parseInt(arr1[0]) - Number.parseInt(arr2[0]);
            jindex++
          ) {
            temp =
              Number.parseInt(arr1[0]) -
              Number.parseInt(jindex) +
              "_" +
              Number.parseInt(arr1[1]) +
              "|Vertical";
            arrTrain.push(temp);
          }
        } else if (
          Number.parseInt(arr1[1]) == Number.parseInt(arr2[1]) &&
          Number.parseInt(arr1[0]) < Number.parseInt(arr2[0])
        ) {
          // doc xuong
          for (
            let jindex = 1;
            jindex < Number.parseInt(arr2[0]) - Number.parseInt(arr1[0]);
            jindex++
          ) {
            temp =
              Number.parseInt(arr1[0]) +
              Number.parseInt(jindex) +
              "_" +
              Number.parseInt(arr1[1]) +
              "|Vertical";
            arrTrain.push(temp);
          }
        }
      }

      let arrfirst = itemtrain[0].split("_");
      let arrlast = itemtrain[itemtrain.length - 1].split("_");

      if (
        arrfirst[1] > 0 &&
        arrfirst[1] < g - 1 &&
        (arrfirst[0] == 0 || arrfirst[0] == g - 1)
      ) {
        arrTrain.push(itemtrain[0] + "|Vertical");
      }
      if (
        arrfirst[0] > 0 &&
        arrfirst[0] < h - 1 &&
        (arrfirst[1] == 0 || arrfirst[1] == h - 1)
      ) {
        arrTrain.push(itemtrain[0] + "|Horizontal");
      }
      if (
        arrlast[1] > 0 &&
        arrlast[1] < g - 1 &&
        (arrlast[0] == 0 || arrlast[0] == g - 1)
      ) {
        arrTrain.push(itemtrain[itemtrain.length - 1] + "|Vertical");
      }
      if (
        arrlast[0] > 0 &&
        arrlast[0] < h - 1 &&
        (arrlast[1] == 0 || arrlast[1] == h - 1)
      ) {
        arrTrain.push(itemtrain[itemtrain.length - 1] + "|Horizontal");
      }

      let dataTrain = [];
      // this.arraycoordinates.push(arrTrain)
      // console.log(this.arraycoordinates)
      arrTrain.forEach((element) => {
        let trainPath = element.split("|");
        dataTrain.push({
          coordinates: trainPath[0],
          src:
            tram == true
              ? "storage/images/RailroadTram" + trainPath[1] + ".png"
              : "storage/images/Railroad" + trainPath[1] + ".png",
        });
      });
      return dataTrain;
    },

    findItem(item, data) {
      return Object.keys(data).find(
        (key) => data[key] === Number.parseInt(item)
      );
    },

    showDialog(id) {
      this.itemId = id;
      this.dialog = true;
    },

    closeDialog() {
      this.itemId = null;
      this.check = false;
      this.dialog = false;
    },

    destroy(id) {
      axios
        .delete("api/level/delete", {
          params: {
            id: id,
          },
        })
        .then((response) => {
          var index = this.data.findIndex(function (o) {
            return o.id === id;
          });
          if (index !== -1) this.data.splice(index, 1);
          console.log(response);
          this.closeDialog();
        })
        .catch((error) => {
          console.log(error);
          this.closeDialog();
        });
    },

    next() {
      this.searchData.level++;
      this.getDataLevel(this.searchData);
    },
    back() {
      this.searchData.level--;
      this.getDataLevel(this.searchData);
    },
  },

  mounted() {
    this.getEntity();
    this.getVersions();
  },
};
</script>

<style>
.small {
  max-width: 250px;
  max-height: 250px;
  /* margin: 150px auto; */
}
</style>
